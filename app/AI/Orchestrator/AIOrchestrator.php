<?php

namespace App\AI\Orchestrator;

use App\AI\DTO\ChatMessage;
use App\AI\Memory\Contracts\MemoryManager;
use App\AI\Memory\DTO\ConversationMessage;
use App\AI\Orchestrator\DTO\Intent;
use App\AI\Orchestrator\DTO\OrchestratorResult;
use App\AI\Prompts\PromptBuilder;
use App\AI\Prompts\Templates\DomainPrompt;
use App\AI\Prompts\Templates\SystemPrompt;
use App\AI\Services\AIChatService;
use App\AI\Streaming\DTO\StreamResult;
use App\AI\Tools\ToolExecutor;
use App\AI\Tools\ToolRegistry;
use Illuminate\Support\Facades\Log;

/**
 * The central AI Agent coordinator.
 * Implements a ReAct loop with Native LLM Tool Calling.
 */
class AIOrchestrator
{
    public function __construct(
        protected ToolRegistry $registry,
        protected ToolExecutor $executor,
        protected ContextFormatter $formatter,
        protected MemoryManager $memory,
        protected AIChatService $chatService,
    ) {
    }

    /**
     * Run the full agentic loop.
     *
     * @param string $userText         The raw user message
     * @param string $sessionId        The conversation session identifier
     * @param callable $onFlush        Callback invoked with buffered text
     * @param callable|null $shouldCancel  Callback returning true to cancel
     * @return OrchestratorResult
     */
    public function orchestrate(
        string $userText,
        string $sessionId,
        callable $onFlush,
        ?callable $shouldCancel = null,
    ): OrchestratorResult {
        // 1. Retrieve conversation history from memory
        $conversation = $this->memory->getConversation($sessionId);

        // 2. Assemble the initial prompt
        $builder = $this->assemblePrompt($userText, $conversation->messages);

        $maxLoops = 5;
        $loopCount = 0;
        $finalContent = '';
        $toolsUsed = [];
        $totalTokens = 0;

        // 3. ReAct Loop
        while ($loopCount < $maxLoops) {
            $loopCount++;

            if ($shouldCancel && $shouldCancel()) {
                break;
            }

            // Sync call to LLM passing tool definitions
            $response = $this->chatService->send($builder, [
                'tools' => $this->registry->getDefinitions()
            ]);

            if ($response->totalTokens) {
                $totalTokens += $response->totalTokens;
            }

            if (!empty($response->toolCalls)) {
                Log::debug("AI Agent requested tool calls", ['calls' => $response->toolCalls]);
                
                // Append the assistant's tool call request
                $builder->addAssistantToolCalls($response->toolCalls);

                // Execute each tool and append the result
                foreach ($response->toolCalls as $call) {
                    $toolId = $call['id'] ?? '';
                    $toolName = $call['function']['name'] ?? '';
                    $arguments = json_decode($call['function']['arguments'] ?? '{}', true) ?: [];

                    $toolsUsed[] = $toolName;

                    try {
                        $rawResult = $this->executor->execute($toolName, $arguments);
                        // Format the raw result so the LLM gets markdown instead of JSON
                        $formatted = $this->formatter->format([$toolName => $rawResult]);
                        
                        // Add tool response to builder
                        $builder->addToolResponse($toolId, $toolName, $formatted);
                    } catch (\Throwable $e) {
                        Log::error("Agent Tool execution failed: {$toolName}", ['error' => $e->getMessage()]);
                        // Tell the LLM it failed so it can try again
                        $builder->addToolResponse($toolId, $toolName, "Error executing tool: {$e->getMessage()}");
                    }
                }
            } else {
                // No tool calls, this is the final text response
                $finalContent = $response->content ?? '';
                break;
            }
        }

        // 4. Simulate Streaming to satisfy UI
        $chunks = str_split($finalContent, 10);
        foreach ($chunks as $chunk) {
            if ($shouldCancel && $shouldCancel()) {
                break;
            }
            $onFlush($chunk);
            usleep(10000); // 10ms delay to actually look like a stream
        }

        // Create a mocked StreamResult
        $streamResult = new StreamResult(
            content: $finalContent,
            model: $this->chatService->getProvider()->getModel(),
            finishReason: 'stop',
            metrics: new \App\AI\Streaming\DTO\StreamMetrics(
                completionTokens: $totalTokens
            )
        );

        // 5. Save final response to memory
        if (!$streamResult->wasCancelled()) {
            $this->memory->addMessage($sessionId, new ConversationMessage(
                role: 'assistant',
                content: $streamResult->content,
                timestamp: now()->toIso8601String(),
            ));
        }

        Log::debug('AI Agent completed task', [
            'loops' => $loopCount,
            'tools_used' => $toolsUsed,
        ]);

        return new OrchestratorResult(
            streamResult: $streamResult,
            intent: null,
            toolContext: null,
        );
    }

    /**
     * Assemble the full prompt with system instructions, history, and user message.
     *
     * @param string $userText
     * @param array $historyMessages
     * @return PromptBuilder
     */
    protected function assemblePrompt(
        string $userText,
        array $historyMessages,
    ): PromptBuilder {
        $builder = new PromptBuilder();

        // System prompt: identity + rules + domain
        $systemContent = SystemPrompt::get() . "\n\n" . DomainPrompt::get();
        $systemContent .= "\n\nYou have access to internal tools. Use them if you need real-time data to answer the user's question.";
        $builder->setSystemPrompt($systemContent);

        // Append conversation history
        foreach ($historyMessages as $msg) {
            if ($msg->role === 'user') {
                $builder->addUserMessage($msg->content);
            } elseif ($msg->role === 'assistant') {
                $builder->addAssistantMessage($msg->content);
            }
        }

        // Current user message
        $builder->addUserMessage($userText);

        return $builder;
    }
}
