<?php

namespace App\AI\DTO;

/**
 * Data Transfer Object representing a single chat message.
 */
class ChatMessage
{
    /**
     * @param string $role The role of the message sender (e.g., 'system', 'user', 'assistant', 'tool').
     * @param string|null $content The text content of the message.
     * @param array $toolCalls The tool calls requested by the assistant.
     * @param string|null $toolCallId The ID of the tool call this message is responding to.
     * @param string|null $name The name of the tool this message is responding from.
     */
    public function __construct(
        public readonly string $role,
        public readonly ?string $content = null,
        public readonly array $toolCalls = [],
        public readonly ?string $toolCallId = null,
        public readonly ?string $name = null
    ) {
    }

    /**
     * Convert the message to an array format expected by most AI providers.
     */
    public function toArray(): array
    {
        $data = [
            'role' => $this->role,
        ];

        // Ensure content is provided, even if null, when making a tool call
        if ($this->content !== null || !empty($this->toolCalls)) {
            $data['content'] = $this->content;
        }

        if (!empty($this->toolCalls)) {
            $data['tool_calls'] = $this->toolCalls;
        }

        if ($this->toolCallId !== null) {
            $data['tool_call_id'] = $this->toolCallId;
        }

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        return $data;
    }
}
