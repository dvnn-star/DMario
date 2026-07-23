<?php

namespace App\AI\Prompts\Builders;

use App\AI\Prompts\Contracts\Prompt;
use App\AI\Prompts\DTO\PromptRequest;
use App\AI\Prompts\DTO\PromptResponse;
use App\AI\Prompts\Templates\DomainPrompt;
use App\AI\Prompts\Templates\SystemPrompt;

class GeneralPrompt implements Prompt
{
    public function getName(): string
    {
        return 'general';
    }

    public function supports(PromptRequest $request): bool
    {
        // General acts as the fallback. It doesn't strictly 'support' a specific intent, 
        // but rather catches everything else. PromptResolver will handle this.
        return true; 
    }

    public function build(PromptRequest $request): PromptResponse
    {
        $contextJson = empty($request->contextData) 
            ? "No specific context required or available for this general query." 
            : json_encode($request->contextData);

        return new PromptResponse(
            systemPrompt: SystemPrompt::get() . "\nAssist the user with general restaurant queries. Keep answers strictly tied to restaurant operations.",
            domainPrompt: DomainPrompt::get(),
            contextPrompt: $contextJson,
            userPrompt: $request->userMessage,
            metadata: ['builder' => $this->getName()]
        );
    }
}
