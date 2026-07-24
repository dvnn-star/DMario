<?php

use App\AI\Memory\Stores\SessionMemoryStore;
use App\AI\Memory\DTO\Conversation;
use App\AI\Memory\DTO\ConversationMessage;
use Illuminate\Support\Facades\Session;

uses(Tests\TestCase::class);

beforeEach(function () {
    $this->store = new SessionMemoryStore();
    Session::flush();
});

test('it can save and retrieve a conversation', function () {
    $sessionId = 'test_uuid_123';
    
    $messages = [
        new ConversationMessage('user', 'Hello AI', now()->toIso8601String()),
        new ConversationMessage('assistant', 'Hello User', now()->toIso8601String())
    ];
    
    $conversation = new Conversation($sessionId, $messages);
    
    $this->store->save($conversation);
    
    $retrieved = $this->store->get($sessionId);
    
    expect($retrieved)->not->toBeNull()
        ->and($retrieved->sessionId)->toBe($sessionId)
        ->and($retrieved->messages)->toHaveCount(2)
        ->and($retrieved->messages[0]->role)->toBe('user')
        ->and($retrieved->messages[0]->content)->toBe('Hello AI');
});

test('it returns null for non-existent session', function () {
    $retrieved = $this->store->get('fake_session');
    
    expect($retrieved)->toBeNull();
});

test('it can clear a session', function () {
    $sessionId = 'test_uuid_456';
    $conversation = new Conversation($sessionId, []);
    $this->store->save($conversation);
    
    expect($this->store->get($sessionId))->not->toBeNull();
    
    $this->store->clear($sessionId);
    
    expect($this->store->get($sessionId))->toBeNull();
});
