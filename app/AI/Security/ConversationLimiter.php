<?php

namespace App\AI\Security;

use App\AI\DTO\ChatMessage;

/**
 * Limits the size of the conversation history to prevent context window overflow.
 */
class ConversationLimiter
{
    /**
     * @param int $maxHistory The maximum number of interaction turns to keep (excluding system prompt).
     */
    public function __construct(
        protected int $maxHistory = 10
    ) {
    }

    /**
     * Limit the array of chat messages.
     * Always preserves the system prompt if it exists.
     *
     * @param ChatMessage[] $messages
     * @return ChatMessage[]
     */
    public function limit(array $messages): array
    {
        if (count($messages) <= $this->maxHistory + 1) { // +1 for potential system prompt
            return $messages;
        }

        $systemMessages = [];
        $otherMessages = [];

        foreach ($messages as $message) {
            if ($message->role === 'system') {
                $systemMessages[] = $message;
            } else {
                $otherMessages[] = $message;
            }
        }

        // Keep only the most recent $maxHistory messages
        $limitedOtherMessages = array_slice($otherMessages, -$this->maxHistory);

        return array_merge($systemMessages, $limitedOtherMessages);
    }
}
