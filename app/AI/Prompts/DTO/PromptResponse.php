<?php

namespace App\AI\Prompts\DTO;

/**
 * Immutable DTO representing the fully resolved prompt ready for the AI Provider.
 */
readonly class PromptResponse
{
    /**
     * @param string $systemPrompt Core AI identity and strict operational rules
     * @param string $domainPrompt Constraints limiting AI to restaurant topics
     * @param string $contextPrompt JSON string or text containing business data
     * @param string $userPrompt The actual user query
     * @param array $metadata Extra metadata (e.g., tokens estimated, chosen builder)
     */
    public function __construct(
        public string $systemPrompt,
        public string $domainPrompt,
        public string $contextPrompt,
        public string $userPrompt,
        public array $metadata = []
    ) {
    }

    /**
     * Helper to format the prompt for a standard LLM payload
     */
    public function toProviderArray(): array
    {
        return [
            ['role' => 'system', 'content' => $this->systemPrompt . "\n\n" . $this->domainPrompt],
            ['role' => 'system', 'content' => "RESTAURANT DATA CONTEXT:\n" . $this->contextPrompt],
            ['role' => 'user', 'content' => $this->userPrompt],
        ];
    }
}
