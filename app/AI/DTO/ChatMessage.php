<?php

namespace App\AI\DTO;

/**
 * Data Transfer Object representing a single chat message.
 */
class ChatMessage
{
    /**
     * @param string $role The role of the message sender (e.g., 'system', 'user', 'assistant').
     * @param string $content The text content of the message.
     */
    public function __construct(
        public readonly string $role,
        public readonly string $content
    ) {
    }

    /**
     * Convert the message to an array format expected by most AI providers.
     */
    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'content' => $this->content,
        ];
    }
}
