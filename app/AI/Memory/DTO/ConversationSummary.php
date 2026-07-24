<?php

namespace App\AI\Memory\DTO;

/**
 * Immutable representation of compressed historic conversation.
 */
readonly class ConversationSummary
{
    /**
     * @param string $summaryText The compressed narrative of past messages
     * @param int $tokenEstimate Estimated token size
     * @param array $metadata Optional metadata about when/how it was summarized
     */
    public function __construct(
        public string $summaryText,
        public int $tokenEstimate = 0,
        public array $metadata = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'summary_text' => $this->summaryText,
            'token_estimate' => $this->tokenEstimate,
            'metadata' => $this->metadata,
        ];
    }
}
