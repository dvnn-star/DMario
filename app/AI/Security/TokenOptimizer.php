<?php

namespace App\AI\Security;

use App\AI\DTO\ChatMessage;

/**
 * Optimizes the token footprint of the messages before sending.
 */
class TokenOptimizer
{
    /**
     * Compress and optimize the messages array.
     *
     * @param ChatMessage[] $messages
     * @return ChatMessage[]
     */
    public function optimize(array $messages): array
    {
        $optimizedMessages = [];

        foreach ($messages as $message) {
            // Compress whitespace to save tokens (only if content is not null)
            $content = $message->content;
            if ($content !== null) {
                $content = preg_replace('/\s+/', ' ', $content);
                $content = trim($content);
            }

            $optimizedMessages[] = new ChatMessage(
                $message->role,
                $content,
                $message->toolCalls,
                $message->toolCallId,
                $message->name
            );
        }

        return $optimizedMessages;
    }
}
