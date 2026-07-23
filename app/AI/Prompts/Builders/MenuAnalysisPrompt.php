<?php

namespace App\AI\Prompts\Builders;

use App\AI\Prompts\Contracts\Prompt;
use App\AI\Prompts\DTO\PromptRequest;
use App\AI\Prompts\DTO\PromptResponse;
use App\AI\Prompts\Templates\DomainPrompt;
use App\AI\Prompts\Templates\SystemPrompt;

class MenuAnalysisPrompt implements Prompt
{
    public function getName(): string
    {
        return 'menus';
    }

    public function supports(PromptRequest $request): bool
    {
        $message = strtolower($request->userMessage);
        return str_contains($message, 'menu') || 
               str_contains($message, 'food') ||
               str_contains($message, 'dish') ||
               str_contains($message, 'drink') ||
               str_contains($message, 'selling');
    }

    public function build(PromptRequest $request): PromptResponse
    {
        $menuContext = $request->contextData['menus'] ?? [];
        $contextJson = json_encode(['menus' => $menuContext]);

        return new PromptResponse(
            systemPrompt: SystemPrompt::get() . "\nProvide insights into menu item performance. Suggest promotions for underperforming items if appropriate.",
            domainPrompt: DomainPrompt::get(),
            contextPrompt: $contextJson,
            userPrompt: $request->userMessage,
            metadata: ['builder' => $this->getName()]
        );
    }
}
