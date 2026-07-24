<?php

namespace App\AI\Prompts\Builders;

use App\AI\Prompts\Contracts\Prompt;
use App\AI\Prompts\DTO\PromptRequest;
use App\AI\Prompts\DTO\PromptResponse;
use App\AI\Prompts\Templates\DomainPrompt;
use App\AI\Prompts\Templates\SystemPrompt;

class ReservationPrompt implements Prompt
{
    public function getName(): string
    {
        return 'reservations';
    }

    public function supports(PromptRequest $request): bool
    {
        $message = strtolower($request->userMessage);
        return str_contains($message, 'reservation') || 
               str_contains($message, 'booking') ||
               str_contains($message, 'book a table');
    }

    public function build(PromptRequest $request): PromptResponse
    {
        $resContext = $request->contextData['reservations'] ?? [];
        $contextJson = json_encode(['reservations' => $resContext]);

        return new PromptResponse(
            systemPrompt: SystemPrompt::get() . "\nSummarize table reservations. Point out busy periods or peak hours.",
            domainPrompt: DomainPrompt::get(),
            contextPrompt: $contextJson,
            userPrompt: $request->userMessage,
            metadata: ['builder' => $this->getName()]
        );
    }
}
