<?php

namespace App\AI\Prompts\Builders;

use App\AI\Prompts\Contracts\Prompt;
use App\AI\Prompts\DTO\PromptRequest;
use App\AI\Prompts\DTO\PromptResponse;
use App\AI\Prompts\Templates\DomainPrompt;
use App\AI\Prompts\Templates\SystemPrompt;

class SalesAnalysisPrompt implements Prompt
{
    public function getName(): string
    {
        return 'sales';
    }

    public function supports(PromptRequest $request): bool
    {
        $message = strtolower($request->userMessage);
        return str_contains($message, 'sales') || 
               str_contains($message, 'revenue') ||
               str_contains($message, 'income');
    }

    public function build(PromptRequest $request): PromptResponse
    {
        $salesContext = $request->contextData['sales'] ?? [];
        $contextJson = json_encode(['sales' => $salesContext]);

        return new PromptResponse(
            systemPrompt: SystemPrompt::get() . "\nAnalyze the financial sales data. Highlight growth trends and revenue.",
            domainPrompt: DomainPrompt::get(),
            contextPrompt: $contextJson,
            userPrompt: $request->userMessage,
            metadata: ['builder' => $this->getName()]
        );
    }
}
