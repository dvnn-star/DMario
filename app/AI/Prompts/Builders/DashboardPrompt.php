<?php

namespace App\AI\Prompts\Builders;

use App\AI\Prompts\Contracts\Prompt;
use App\AI\Prompts\DTO\PromptRequest;
use App\AI\Prompts\DTO\PromptResponse;
use App\AI\Prompts\Templates\DomainPrompt;
use App\AI\Prompts\Templates\SystemPrompt;

class DashboardPrompt implements Prompt
{
    public function getName(): string
    {
        return 'dashboard';
    }

    public function supports(PromptRequest $request): bool
    {
        $message = strtolower($request->userMessage);
        return str_contains($message, 'dashboard') || 
               str_contains($message, 'overall performance') ||
               str_contains($message, 'summary today');
    }

    public function build(PromptRequest $request): PromptResponse
    {
        // Extract context safely, defaulting to empty JSON if missing
        $dashboardContext = $request->contextData['dashboard'] ?? [];
        $contextJson = json_encode(['dashboard' => $dashboardContext]);

        return new PromptResponse(
            systemPrompt: SystemPrompt::get() . "\nProvide a high-level executive summary of the dashboard metrics.",
            domainPrompt: DomainPrompt::get(),
            contextPrompt: $contextJson,
            userPrompt: $request->userMessage,
            metadata: ['builder' => $this->getName()]
        );
    }
}
