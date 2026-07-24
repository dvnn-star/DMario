<?php

namespace App\AI\Memory\Contracts;

use App\AI\Memory\DTO\Conversation;
use App\AI\Memory\DTO\ConversationMessage;

interface MemoryManager
{
    /**
     * Retrieve the optimized conversation state.
     */
    public function getConversation(string $sessionId): Conversation;

    /**
     * Add a new message to the conversation and run memory strategies (sliding window/summarization).
     */
    public function addMessage(string $sessionId, ConversationMessage $message): Conversation;

    /**
     * Completely clear the conversation history.
     */
    public function forget(string $sessionId): void;
}
