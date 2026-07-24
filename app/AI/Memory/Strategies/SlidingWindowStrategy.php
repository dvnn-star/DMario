<?php

namespace App\AI\Memory\Strategies;

use App\AI\Memory\DTO\Conversation;

class SlidingWindowStrategy
{
    /**
     * Applies the sliding window to the conversation.
     * Returns an array with exactly two elements:
     * 0 => The messages that fell OUT of the window (to be summarized)
     * 1 => The messages that remain IN the window
     *
     * @param Conversation $conversation
     * @param int $maxHistory
     * @return array<array>
     */
    public function apply(Conversation $conversation, int $maxHistory): array
    {
        $allMessages = $conversation->messages;
        
        if (count($allMessages) <= $maxHistory) {
            return [[], $allMessages];
        }

        // Calculate how many messages need to be pruned from the beginning
        $pruneCount = count($allMessages) - $maxHistory;

        $prunedMessages = array_slice($allMessages, 0, $pruneCount);
        $keptMessages = array_slice($allMessages, $pruneCount);

        return [$prunedMessages, $keptMessages];
    }
}
