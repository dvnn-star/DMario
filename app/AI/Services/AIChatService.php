<?php

namespace App\AI\Services;

use App\AI\Contracts\AIProvider;
use App\AI\DTO\AIResponse;
use App\AI\DTO\ChatMessage;
use App\AI\Prompts\PromptBuilder;
use App\AI\Security\InputSanitizer;
use App\AI\Security\PromptGuard;
use App\AI\Security\DomainGuard;
use App\AI\Security\ConversationLimiter;
use App\AI\Security\TokenOptimizer;
use App\AI\Security\ResponseValidator;
use App\AI\Streaming\Buffer\StreamBuffer;
use App\AI\Streaming\DTO\StreamChunk;
use App\AI\Streaming\DTO\StreamResult;
use App\AI\Streaming\StreamResponse;
use Illuminate\Support\Facades\Log;

/**
 * High-level service for interacting with AI capabilities securely.
 */
class AIChatService
{
    public function __construct(
        protected AIProvider $provider,
        protected InputSanitizer $sanitizer,
        protected PromptGuard $promptGuard,
        protected DomainGuard $domainGuard,
        protected ConversationLimiter $limiter,
        protected TokenOptimizer $optimizer,
        protected ResponseValidator $responseValidator
    ) {
    }

    /**
     * Send a simple prompt to the AI and get a response.
     */
    public function ask(string $prompt, ?string $systemPrompt = null): AIResponse
    {
        $builder = new PromptBuilder();
        
        if ($systemPrompt) {
            $builder->setSystemPrompt($systemPrompt);
        }

        $builder->addUserMessage($prompt);

        return $this->send($builder);
    }

    /**
     * Send an existing PromptBuilder instance to the AI through the security pipeline.
     */
    public function send(PromptBuilder $builder, array $options = []): AIResponse
    {
        $messages = $this->prepareMessages($builder);

        // Send to Provider
        $response = $this->provider->sendMessage($messages, $options);

        // Validate Output
        return $this->responseValidator->validate($response);
    }

    /**
     * Stream an existing PromptBuilder instance through the security pipeline.
     * Invokes the callback for each buffered chunk of tokens.
     *
     * @param PromptBuilder $builder
     * @param callable(string $bufferedText): void $onFlush Called with buffered text when the buffer flushes
     * @param callable|null $shouldCancel A callable returning true if the stream should be cancelled
     * @param array $options Provider-specific options
     * @return StreamResult
     */
    public function sendStreaming(
        PromptBuilder $builder,
        callable $onFlush,
        ?callable $shouldCancel = null,
        array $options = []
    ): StreamResult {
        $messages = $this->prepareMessages($builder);

        // Open the stream
        $streamResponse = $this->provider->stream($messages, $options);
        $buffer = new StreamBuffer();

        try {
            foreach ($streamResponse->chunks() as $chunk) {
                // Check for cancellation
                if ($shouldCancel && $shouldCancel()) {
                    $streamResponse->cancel();
                    break;
                }

                // Append token to buffer
                if ($chunk->token !== '') {
                    $buffer->append($chunk->token);
                }

                // Flush buffer on schedule
                if ($buffer->shouldFlush()) {
                    $onFlush($buffer->flush());
                }
            }

            // Force flush any remaining content
            if ($buffer->hasContent()) {
                $onFlush($buffer->forceFlush());
            }

        } catch (\Throwable $e) {
            Log::error('Stream error: ' . $e->getMessage());

            // Force flush what we have before re-throwing
            if ($buffer->hasContent()) {
                $onFlush($buffer->forceFlush());
            }

            throw $e;
        }

        $metrics = $streamResponse->getMetrics();

        return new StreamResult(
            content: $streamResponse->getAccumulatedContent(),
            model: $this->provider->getModel(),
            finishReason: $streamResponse->isCancelled() ? 'cancelled' : 'stop',
            metrics: $metrics
        );
    }

    /**
     * Run the full security pipeline on the PromptBuilder messages.
     *
     * @return ChatMessage[]
     */
    protected function prepareMessages(PromptBuilder $builder): array
    {
        $messages = $builder->getMessages();

        // 1. Sanitize and Guard User Inputs
        foreach ($messages as &$message) {
            if ($message->role === 'user') {
                $sanitized = $this->sanitizer->sanitize($message->content);
                $this->promptGuard->check($sanitized);
                $this->domainGuard->check($sanitized);
                
                // Update message content with sanitized version
                $message = new ChatMessage($message->role, $sanitized);
            }
        }

        // 2. Limit Conversation History
        $messages = $this->limiter->limit($messages);

        // 3. Optimize Tokens
        $messages = $this->optimizer->optimize($messages);

        return $messages;
    }

    /**
     * Temporarily switch the model used for the next request on this service instance.
     */
    public function usingModel(string $model): self
    {
        $this->provider->setModel($model);
        return $this;
    }

    /**
     * Get the underlying provider.
     */
    public function getProvider(): AIProvider
    {
        return $this->provider;
    }
}
