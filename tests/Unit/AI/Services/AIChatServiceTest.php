<?php

use App\AI\Contracts\AIProvider;
use App\AI\DTO\AIResponse;
use App\AI\Prompts\PromptBuilder;
use App\AI\Security\DomainGuard;
use App\AI\Security\InputSanitizer;
use App\AI\Security\PromptGuard;
use App\AI\Security\ConversationLimiter;
use App\AI\Security\TokenOptimizer;
use App\AI\Security\ResponseValidator;
use App\AI\Services\AIChatService;
use App\AI\Exceptions\SecurityException;
use Mockery\MockInterface;

uses(Tests\TestCase::class);

beforeEach(function () {
    $this->provider = Mockery::mock(AIProvider::class);
    $this->sanitizer = Mockery::mock(InputSanitizer::class);
    $this->promptGuard = Mockery::mock(PromptGuard::class);
    $this->domainGuard = Mockery::mock(DomainGuard::class);
    $this->limiter = Mockery::mock(ConversationLimiter::class);
    $this->optimizer = Mockery::mock(TokenOptimizer::class);
    $this->responseValidator = Mockery::mock(ResponseValidator::class);

    $this->service = new AIChatService(
        $this->provider,
        $this->sanitizer,
        $this->promptGuard,
        $this->domainGuard,
        $this->limiter,
        $this->optimizer,
        $this->responseValidator
    );
});

afterEach(function () {
    Mockery::close();
});

test('it executes the security middleware pipeline in the correct order and invokes the provider', function () {
    $builder = new PromptBuilder();
    $builder->addUserMessage("Berapa omzet hari ini?");

    $mockResponse = new AIResponse(
        content: "Omzet hari ini adalah Rp 1.000.000", 
        model: "test-model", 
        promptTokens: 10, 
        completionTokens: 10, 
        totalTokens: 20
    );

    // 1. Sanitizer
    $this->sanitizer->shouldReceive('sanitize')
        ->once()
        ->with("Berapa omzet hari ini?")
        ->andReturn("Berapa omzet hari ini?");

    // 2. PromptGuard
    $this->promptGuard->shouldReceive('check')
        ->once()
        ->with("Berapa omzet hari ini?");

    // 3. DomainGuard
    $this->domainGuard->shouldReceive('check')
        ->once()
        ->with("Berapa omzet hari ini?");

    // 4. Limiter
    $this->limiter->shouldReceive('limit')
        ->once()
        ->andReturnUsing(fn($messages) => $messages);

    // 5. Optimizer
    $this->optimizer->shouldReceive('optimize')
        ->once()
        ->andReturnUsing(fn($messages) => $messages);

    // 6. Provider is invoked exactly once
    $this->provider->shouldReceive('sendMessage')
        ->once()
        ->andReturn($mockResponse);

    // 7. Validator
    $this->responseValidator->shouldReceive('validate')
        ->once()
        ->with($mockResponse)
        ->andReturn($mockResponse);

    $response = $this->service->send($builder);

    expect($response->content)->toBe("Omzet hari ini adalah Rp 1.000.000");
});

test('it halts execution immediately if DomainGuard throws SecurityException', function () {
    $builder = new PromptBuilder();
    $builder->addUserMessage("Write a python script");

    $this->sanitizer->shouldReceive('sanitize')
        ->once()
        ->andReturn("Write a python script");

    $this->promptGuard->shouldReceive('check')
        ->once();

    $this->domainGuard->shouldReceive('check')
        ->once()
        ->andThrow(new SecurityException("Domain violation"));

    // Provider should NEVER be called
    $this->provider->shouldReceive('sendMessage')->never();
    
    // Optimizer and Limiter should NEVER be called
    $this->limiter->shouldReceive('limit')->never();
    $this->optimizer->shouldReceive('optimize')->never();

    $this->service->send($builder);
})->throws(SecurityException::class, "Domain violation");
