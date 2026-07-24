<?php

namespace App\AI\Prompts\Builders;

use App\AI\Prompts\Contracts\Prompt;
use App\AI\Prompts\DTO\PromptRequest;
use App\AI\Prompts\DTO\PromptResponse;
use App\AI\Prompts\Templates\DomainPrompt;
use App\AI\Prompts\Templates\SystemPrompt;

class OrderInsightPrompt implements Prompt
{
    public function getName(): string
    {
        return 'orders';
    }

    public function supports(PromptRequest $request): bool
    {
        $message = strtolower($request->userMessage);
        return str_contains($message, 'order') || 
               str_contains($message, 'pending') ||
               str_contains($message, 'kitchen');
    }

    public function build(PromptRequest $request): PromptResponse
    {
        $orderContext = $request->contextData['orders'] ?? [];
        $contextJson = json_encode(['orders' => $orderContext]);

        return new PromptResponse(
            systemPrompt: SystemPrompt::get() . "\nDiscuss the current order backlog, preparation times, and completion rates.",
            domainPrompt: DomainPrompt::get(),
            contextPrompt: $contextJson,
            userPrompt: $request->userMessage,
            metadata: ['builder' => $this->getName()]
        );
    }
}
