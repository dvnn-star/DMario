<?php

namespace App\AI\Memory\Strategies;

use App\AI\Memory\Contracts\MemorySummarizer;
use App\AI\Memory\DTO\ConversationMessage;
use App\AI\Memory\DTO\ConversationSummary;

class SummarizationStrategy implements MemorySummarizer
{
    public function summarize(array $messages, ?ConversationSummary $currentSummary = null): ConversationSummary
    {
        // This is a deterministic compression strategy since LLM calls are forbidden here.
        $newText = "";
        
        foreach ($messages as $message) {
            // Drop common conversational filler/acknowledgments
            if ($message->role === 'assistant') {
                if (preg_match('/^(sure|okay|i can help|here is the data)/i', $message->content)) {
                    continue; // Skip useless assistant tokens
                }
            }

            // Truncate long messages aggressively
            $truncatedContent = mb_strimwidth($message->content, 0, 150, '...');
            $newText .= "[{$message->role}] {$truncatedContent}\n";
        }

        $existingText = $currentSummary ? $currentSummary->summaryText . "\n" : "";
        $combinedText = trim($existingText . $newText);

        // Very rough token estimation (1 token ~= 4 chars)
        $tokenEstimate = (int) ceil(strlen($combinedText) / 4);

        return new ConversationSummary(
            summaryText: $combinedText,
            tokenEstimate: $tokenEstimate,
            metadata: ['method' => 'deterministic']
        );
    }
}
