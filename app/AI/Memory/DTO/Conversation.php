<?php

namespace App\AI\Memory\DTO;

/**
 * Immutable master container for the entire conversation state.
 */
readonly class Conversation
{
    /**
     * @param string $sessionId Unique identifier for this conversation
     * @param ConversationMessage[] $messages The sliding window of recent messages
     * @param ConversationSummary|null $summary The compressed older history
     * @param MemoryContext|null $context Environmental context flags
     */
    public function __construct(
        public string $sessionId,
        public array $messages = [],
        public ?ConversationSummary $summary = null,
        public ?MemoryContext $context = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'session_id' => $this->sessionId,
            'messages' => array_map(fn($m) => $m->toArray(), $this->messages),
            'summary' => $this->summary?->toArray(),
            'context' => $this->context?->toArray(),
        ];
    }
}
