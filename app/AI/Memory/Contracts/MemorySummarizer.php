<?php

namespace App\AI\Memory\Contracts;

use App\AI\Memory\DTO\ConversationMessage;
use App\AI\Memory\DTO\ConversationSummary;

interface MemorySummarizer
{
    /**
     * Take an array of old messages and a current summary, and return a new compressed summary.
     * 
     * @param ConversationMessage[] $messages
     * @param ConversationSummary|null $currentSummary
     * @return ConversationSummary
     */
    public function summarize(array $messages, ?ConversationSummary $currentSummary = null): ConversationSummary;
}
