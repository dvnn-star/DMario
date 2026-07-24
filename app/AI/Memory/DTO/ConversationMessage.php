<?php

namespace App\AI\Memory\DTO;

/**
 * Immutable representation of a single conversation turn.
 */
readonly class ConversationMessage
{
    /**
     * @param string $role e.g., 'user', 'assistant'
     * @param string $content The text content
     * @param string $timestamp ISO8601 timestamp
     * @param array $metadata Any extra data about this specific turn
     */
    public function __construct(
        public string $role,
        public string $content,
        public string $timestamp,
        public array $metadata = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'content' => $this->content,
            'timestamp' => $this->timestamp,
            'metadata' => $this->metadata,
        ];
    }
}
