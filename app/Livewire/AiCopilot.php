<?php

namespace App\Livewire;

use Livewire\Component;
use App\Repositories\Contracts\DashboardRepositoryInterface;
use App\AI\Memory\Contracts\MemoryManager;
use App\AI\Memory\DTO\ConversationMessage;
use App\AI\Orchestrator\AIOrchestrator;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Support\Facades\Log;

class AiCopilot extends Component
{
    public string $sessionId;
    public array $messages = [];
    public string $prompt = '';
    public array $insights = [];

    public bool $isOpen = false;
    public bool $hasInitialized = false;

    // Streaming state
    public bool $isSending = false;
    public bool $isStreaming = false;
    public string $streamedContent = '';
    public bool $cancelRequested = false;

    public function mount()
    {
        $activeId = session()->get('ai_active_session_id');

        if (!$activeId) {
            $prefix = auth()->id() ? 'user_' . auth()->id() . '_' : 'guest_';
            $activeId = $prefix . Str::uuid();
            
            session()->put('ai_active_session_id', $activeId);
            
            $history = session()->get('ai_session_history', []);
            $history[] = $activeId;
            session()->put('ai_session_history', $history);
        }

        $this->sessionId = $activeId;
    }

    public function startNewChat()
    {
        // 1. Generate new UUID
        $prefix = auth()->id() ? 'user_' . auth()->id() . '_' : 'guest_';
        $newSessionId = $prefix . Str::uuid();
        
        // 2. Archive it in history
        $history = session()->get('ai_session_history', []);
        $history[] = $newSessionId;
        session()->put('ai_session_history', $history);
        
        // 3. Make it active
        session()->put('ai_active_session_id', $newSessionId);
        $this->sessionId = $newSessionId;
        
        // 4. Reset UI State
        $this->messages = [];
        $this->streamedContent = '';
        $this->isStreaming = false;
        $this->isSending = false;
        $this->cancelRequested = false;
    }

    public function toggleCopilot(DashboardRepositoryInterface $dashboardRepo, MemoryManager $memoryManager)
    {
        $this->isOpen = !$this->isOpen;

        if ($this->isOpen) {
            $this->dispatch('ai-copilot-opened');
            
            if (!$this->hasInitialized) {
                $this->loadInsights($dashboardRepo);
                $this->loadHistory($memoryManager);
                $this->hasInitialized = true;
            }
        }
    }

    protected function loadInsights(DashboardRepositoryInterface $dashboardRepo)
    {
        $summary = $dashboardRepo->getDashboardSummary();
        
        $this->insights = [
            'revenue' => $summary->todaySales->revenue,
            'pending_orders' => $summary->pendingOrders,
            'reservations' => $summary->todayReservations,
            'average_order' => $summary->todaySales->averageOrderValue,
        ];
    }

    protected function loadHistory(MemoryManager $memoryManager)
    {
        $conversation = $memoryManager->getConversation($this->sessionId);
        
        $this->messages = array_map(function ($msg) {
            return [
                'role' => $msg->role,
                'content' => $msg->content,
                'timestamp' => $msg->timestamp,
            ];
        }, $conversation->messages);
    }

    /**
     * Polled by the frontend to fetch any new messages added by background queue jobs.
     */
    public function checkForNewMessages(MemoryManager $memoryManager)
    {
        if (!$this->isOpen || $this->isSending || $this->isStreaming) {
            return;
        }

        $conversation = $memoryManager->getConversation($this->sessionId);
        $serverMessageCount = count($conversation->messages);
        $localMessageCount = count($this->messages);

        if ($serverMessageCount > $localMessageCount) {
            // Re-sync messages
            $this->loadHistory($memoryManager);
            
            // Trigger UI scroll
            $this->dispatch('ai-copilot-messages-updated');
        }
    }

    /**
     * Send a message through the AI Orchestrator pipeline.
     *
     * Flow: InputGuard → IntentClassify → ToolRoute → ToolExecute →
     *       ContextFormat → MemoryRetrieve → PromptAssemble → LLMStream → MemoryUpdate
     */
    public function sendMessage(MemoryManager $memoryManager, AIOrchestrator $orchestrator)
    {
        $this->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        $userText = $this->prompt;
        $this->prompt = '';
        $this->isSending = true;
        $this->isStreaming = true;
        $this->streamedContent = '';
        $this->cancelRequested = false;

        $timestamp = now()->toIso8601String();

        // 1. Add user message to UI immediately
        $this->messages[] = [
            'role' => 'user',
            'content' => $userText,
            'timestamp' => $timestamp,
        ];

        // 2. Add user message to Memory
        $memoryManager->addMessage($this->sessionId, new ConversationMessage(
            role: 'user',
            content: $userText,
            timestamp: $timestamp
        ));

        try {
            // 3. Run the full orchestration pipeline
            $result = $orchestrator->orchestrate(
                userText: $userText,
                sessionId: $this->sessionId,
                onFlush: function (string $bufferedText) {
                    $this->streamedContent .= $bufferedText;
                    $this->stream('streamedContent', $bufferedText, true);
                },
                shouldCancel: fn () => $this->cancelRequested,
            );

            // 4. Finalize
            $this->isStreaming = false;

            if (!$result->wasCancelled()) {
                $aiTimestamp = now()->toIso8601String();

                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => $result->streamResult->content,
                    'timestamp' => $aiTimestamp,
                ];

                // Memory update is already handled inside AIOrchestrator
            }

            $this->streamedContent = '';

            Log::debug('AI Orchestrator completed', [
                'intent' => $result->intent?->name ?? 'ReAct',
                'confidence' => $result->intent?->confidence ?? 1.0,
                'tools_used' => $result->toolContext ? array_keys($result->toolContext->results) : [],
            ]);

        } catch (Throwable $e) {
            Log::error('AI Copilot Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            
            $this->isStreaming = false;
            $this->streamedContent = '';

            $this->messages[] = [
                'role' => 'system',
                'content' => 'Unable to reach AI Provider. Please try again.',
                'timestamp' => now()->toIso8601String(),
            ];
        }

        $this->isSending = false;
    }

    /**
     * Cancel the current streaming response.
     */
    public function cancelStream()
    {
        $this->cancelRequested = true;
        $this->isStreaming = false;
        $this->isSending = false;
    }

    public function render()
    {
        return view('livewire.ai-copilot');
    }
}
