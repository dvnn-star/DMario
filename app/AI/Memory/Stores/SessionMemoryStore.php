<?php

namespace App\AI\Memory\Stores;

use App\AI\Memory\Contracts\MemoryStore;
use App\AI\Memory\DTO\Conversation;
use App\AI\Memory\DTO\ConversationMessage;
use App\AI\Memory\DTO\ConversationSummary;
use App\AI\Memory\DTO\MemoryContext;
use Illuminate\Support\Facades\Session;

class SessionMemoryStore implements MemoryStore
{
    protected function getSessionKey(string $sessionId): string
    {
        return "ai_memory.{$sessionId}";
    }

    public function get(string $sessionId): ?Conversation
    {
        $data = Session::get($this->getSessionKey($sessionId));

        if (!$data) {
            return null;
        }

        // Rehydrate Messages
        $messages = [];
        foreach ($data['messages'] ?? [] as $msg) {
            $messages[] = new ConversationMessage(
                role: $msg['role'],
                content: $msg['content'],
                timestamp: $msg['timestamp'],
                metadata: $msg['metadata'] ?? []
            );
        }

        // Rehydrate Summary
        $summary = null;
        if (isset($data['summary'])) {
            $summary = new ConversationSummary(
                summaryText: $data['summary']['summary_text'],
                tokenEstimate: $data['summary']['token_estimate'],
                metadata: $data['summary']['metadata'] ?? []
            );
        }

        // Rehydrate Context
        $context = null;
        if (isset($data['context'])) {
            $context = new MemoryContext(
                currentTopic: $data['context']['current_topic'] ?? null,
                activePage: $data['context']['active_page'] ?? null,
                activeEntity: $data['context']['active_entity'] ?? null
            );
        }

        return new Conversation(
            sessionId: $sessionId,
            messages: $messages,
            summary: $summary,
            context: $context
        );
    }

    public function save(Conversation $conversation): void
    {
        Session::put($this->getSessionKey($conversation->sessionId), $conversation->toArray());
    }

    public function clear(string $sessionId): void
    {
        Session::forget($this->getSessionKey($sessionId));
    }
}
