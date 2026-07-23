<?php

namespace App\AI\Services;

use App\AI\Contracts\AIProvider;
use App\AI\DTO\AIResponse;
use App\AI\Prompts\PromptBuilder;
use App\AI\Security\InputSanitizer;
use App\AI\Security\PromptGuard;
use App\AI\Security\DomainGuard;
use App\AI\Security\ConversationLimiter;
use App\AI\Security\TokenOptimizer;
use App\AI\Security\ResponseValidator;
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
        $messages = $builder->getMessages();
        
        // 1. Sanitize and Guard User Inputs
        foreach ($messages as &$message) {
            if ($message->role === 'user') {
                $sanitized = $this->sanitizer->sanitize($message->content);
                $this->promptGuard->check($sanitized);
                $this->domainGuard->check($sanitized);
                
                // Update message content with sanitized version
                $message = new \App\AI\DTO\ChatMessage($message->role, $sanitized);
            }
        }

        // 2. Limit Conversation History
        $messages = $this->limiter->limit($messages);

        // 3. Optimize Tokens
        $messages = $this->optimizer->optimize($messages);

        // 4. Send to Provider
        $response = $this->provider->sendMessage($messages, $options);

        // 5. Validate Output
        return $this->responseValidator->validate($response);
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
