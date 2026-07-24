<?php

namespace App\AI\Memory\Contracts;

use App\AI\Memory\DTO\Conversation;

interface MemoryStore
{
    /**
     * Retrieve a conversation by its session ID.
     */
    public function get(string $sessionId): ?Conversation;

    /**
     * Save or update a conversation.
     */
    public function save(Conversation $conversation): void;

    /**
     * Clear a conversation from memory.
     */
    public function clear(string $sessionId): void;
}
