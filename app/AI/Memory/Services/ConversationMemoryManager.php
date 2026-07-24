<?php

namespace App\AI\Memory\Services;

use App\AI\Memory\Contracts\MemoryManager;
use App\AI\Memory\Contracts\MemoryStore;
use App\AI\Memory\Contracts\MemorySummarizer;
use App\AI\Memory\DTO\Conversation;
use App\AI\Memory\DTO\ConversationMessage;
use App\AI\Memory\Strategies\SlidingWindowStrategy;

class ConversationMemoryManager implements MemoryManager
{
    public function __construct(
        protected MemoryStore $store,
        protected MemorySummarizer $summarizer,
        protected SlidingWindowStrategy $slidingWindow
    ) {
    }

    public function getConversation(string $sessionId): Conversation
    {
        $conversation = $this->store->get($sessionId);

        if (!$conversation) {
            $conversation = new Conversation(sessionId: $sessionId);
            $this->store->save($conversation);
        }

        return $conversation;
    }

    public function addMessage(string $sessionId, ConversationMessage $message): Conversation
    {
        $conversation = $this->getConversation($sessionId);

        // 1. Add the new message
        $messages = $conversation->messages;
        $messages[] = $message;

        // 2. Apply Sliding Window
        $maxHistory = config('ai.memory.max_history', 10);
        $conversationWithNewMessages = new Conversation(
            sessionId: $sessionId,
            messages: $messages,
            summary: $conversation->summary,
            context: $conversation->context
        );

        [$prunedMessages, $keptMessages] = $this->slidingWindow->apply($conversationWithNewMessages, $maxHistory);

        // 3. Apply Summarization if messages fell out of the window
        $newSummary = $conversation->summary;
        if (!empty($prunedMessages)) {
            $newSummary = $this->summarizer->summarize($prunedMessages, $newSummary);
        }

        // 4. Build final optimized conversation
        $optimizedConversation = new Conversation(
            sessionId: $sessionId,
            messages: $keptMessages,
            summary: $newSummary,
            context: $conversation->context
        );

        // 5. Persist
        $this->store->save($optimizedConversation);

        return $optimizedConversation;
    }

    public function forget(string $sessionId): void
    {
        $this->store->clear($sessionId);
    }
}
