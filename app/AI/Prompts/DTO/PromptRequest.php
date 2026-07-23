<?php

namespace App\AI\Prompts\DTO;

/**
 * Immutable DTO encapsulating data needed to build a prompt.
 */
readonly class PromptRequest
{
    /**
     * @param string $userMessage The raw question/message from the user
     * @param array $conversationSummary Prior chat context (if any)
     * @param array $contextData The business data from the Context Layer
     * @param string $userRole e.g., 'manager', 'staff'
     * @param string $language Expected response language (e.g., 'en', 'id')
     * @param array $metadata Any extra data required for the prompt
     */
    public function __construct(
        public string $userMessage,
        public array $conversationSummary = [],
        public array $contextData = [],
        public string $userRole = 'staff',
        public string $language = 'en',
        public array $metadata = []
    ) {
    }
}
