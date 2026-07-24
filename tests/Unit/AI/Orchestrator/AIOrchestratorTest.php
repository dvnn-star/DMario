<?php

use App\AI\DTO\AIResponse;
use App\AI\Memory\Contracts\MemoryManager;
use App\AI\Memory\DTO\Conversation;
use App\AI\Memory\DTO\ConversationMessage;
use App\AI\Orchestrator\AIOrchestrator;
use App\AI\Orchestrator\ContextFormatter;
use App\AI\Prompts\PromptBuilder;
use App\AI\Services\AIChatService;
use App\AI\Tools\ToolExecutor;
use App\AI\Tools\ToolRegistry;
use App\AI\Contracts\AIProvider;
use Illuminate\Support\Facades\Log;
use Mockery\MockInterface;

uses(Tests\TestCase::class);

beforeEach(function () {
    $this->registry = Mockery::mock(ToolRegistry::class);
    $this->executor = Mockery::mock(ToolExecutor::class);
    $this->formatter = Mockery::mock(ContextFormatter::class);
    $this->memory = new class implements MemoryManager {
        public $conversations = [];
        public $addedMessages = [];
        
        public function getConversation(string $sessionId): Conversation
        {
            return $this->conversations[$sessionId] ?? new Conversation($sessionId, []);
        }
        
        public function addMessage(string $sessionId, ConversationMessage $message): Conversation
        {
            $this->addedMessages[] = $message;
            return new Conversation($sessionId, [$message]);
        }
        
        public function forget(string $sessionId): void {}
    };
    $this->chatService = Mockery::mock(AIChatService::class);

    $this->orchestrator = new AIOrchestrator(
        $this->registry,
        $this->executor,
        $this->formatter,
        $this->memory,
        $this->chatService
    );
});

afterEach(function () {
    Mockery::close();
});

test('it integrates with memory and returns final string without tools', function () {
    $sessionId = 'uuid_123';
    
    // 1. Memory integration
    $this->memory->conversations[$sessionId] = new Conversation($sessionId, []);
        
    $this->registry->shouldReceive('getDefinitions')
        ->once()
        ->andReturn([]);
        
    // 2. Chat Service call (ReAct Loop #1)
    $mockResponse = new AIResponse(
        content: "Hello, I am Dmario AI", 
        model: "test-model", 
        totalTokens: 50
    );
    $this->chatService->shouldReceive('send')
        ->once()
        ->withArgs(function ($builder, $options) {
            return $builder instanceof PromptBuilder && isset($options['tools']);
        })
        ->andReturn($mockResponse);
        
    $this->chatService->shouldReceive('getProvider')
        ->once()
        ->andReturn(Mockery::mock(AIProvider::class, function ($mock) {
            $mock->shouldReceive('getModel')->andReturn('test-model');
        }));

    $result = $this->orchestrator->orchestrate(
        userText: "Hi",
        sessionId: $sessionId,
        onFlush: fn($chunk) => null
    );

    expect($result->streamResult->content)->toBe("Hello, I am Dmario AI")
        ->and($this->memory->addedMessages)->toHaveCount(1)
        ->and($this->memory->addedMessages[0]->content)->toBe("Hello, I am Dmario AI");
});

test('it executes tools correctly and loops to provide final answer', function () {
    $sessionId = 'uuid_456';
    $this->memory->conversations[$sessionId] = new Conversation($sessionId, []);
        
    $this->registry->shouldReceive('getDefinitions')
        ->times(2) // Called in both loops
        ->andReturn([['type' => 'function', 'function' => ['name' => 'analyze_revenue']]]);
        
    // First loop: LLM requests a tool
    $toolCallResponse = new AIResponse(
        content: "", 
        model: "test-model", 
        totalTokens: 50,
        rawResponse: [],
        toolCalls: [
            [
                'id' => 'call_123',
                'type' => 'function',
                'function' => [
                    'name' => 'analyze_revenue',
                    'arguments' => '{"period":"today"}'
                ]
            ]
        ]
    );
    
    // Second loop: LLM gives final answer based on tool output
    $finalResponse = new AIResponse(
        content: "Revenue today is 1.000.000", 
        model: "test-model", 
        totalTokens: 50
    );

    $this->chatService->shouldReceive('send')
        ->times(2)
        ->andReturn($toolCallResponse, $finalResponse);
        
    // Tool Execution
    $this->executor->shouldReceive('execute')
        ->once()
        ->with('analyze_revenue', ['period' => 'today'])
        ->andReturn(['total_revenue' => 1000000]);
        
    // Context formatting
    $this->formatter->shouldReceive('format')
        ->once()
        ->with(['analyze_revenue' => ['total_revenue' => 1000000]])
        ->andReturn("Total Revenue: 1.000.000");

    $this->chatService->shouldReceive('getProvider')
        ->once()
        ->andReturn(Mockery::mock(AIProvider::class, function ($mock) {
            $mock->shouldReceive('getModel')->andReturn('test-model');
        }));

    $result = $this->orchestrator->orchestrate(
        userText: "Berapa omzet hari ini?",
        sessionId: $sessionId,
        onFlush: fn($chunk) => null
    );

    expect($result->streamResult->content)->toBe("Revenue today is 1.000.000")
        ->and($result->streamResult->metrics->completionTokens)->toBe(100); // 50 + 50 tokens
});
