<div>
    <style>
        /* Scoped CSS for Global Copilot Overlay */

        /* Floating Trigger Button */
        .ai-fab {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 9999px;
            background-color: var(--fi-color-primary-500, #f59e0b);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            z-index: 9999;
            border: none;
            padding: 0;
            overflow: hidden;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .ai-fab:hover {
            transform: scale(1.05);
            background-color: var(--fi-color-primary-600, #d97706);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Backdrop */
        .ai-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(0,0,0,0.3);
            z-index: 9998;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(2px);
        }
        .ai-backdrop.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        /* Sidebar Container */
        .ai-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            width: 100%;
            max-width: 420px;
            background-color: rgb(249, 250, 251);
            z-index: 10000;
            box-shadow: -10px 0 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            border-left: 1px solid rgba(0,0,0,0.05);
        }
        .dark .ai-sidebar {
            background-color: rgb(17, 24, 39); /* Gray-900 */
            border-color: rgba(255,255,255,0.1);
        }
        .ai-sidebar.is-open {
            transform: translateX(0);
        }

        /* Sidebar Header */
        .ai-sidebar-header {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            background-color: white;
        }
        .dark .ai-sidebar-header {
            background-color: rgba(255,255,255,0.02);
            border-color: rgba(255,255,255,0.1);
        }
        .ai-sidebar-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: rgb(17, 24, 39);
        }
        .dark .ai-sidebar-title { color: white; }
        .ai-close-btn {
            background: transparent;
            border: none;
            color: rgb(156, 163, 175);
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }
        .ai-close-btn:hover {
            background-color: rgba(0,0,0,0.05);
            color: rgb(17, 24, 39);
        }
        .dark .ai-close-btn:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }

        /* Chat Panel Internals */
        .ai-chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            scroll-behavior: smooth;
        }

        /* Message Bubbles */
        .ai-msg-row {
            display: flex;
            width: 100%;
        }
        .ai-msg-row.user { justify-content: flex-end; }
        .ai-msg-row.assistant { justify-content: flex-start; }

        .ai-bubble {
            max-width: 85%;
            padding: 1rem 1.25rem;
            border-radius: 1.25rem;
            font-size: 0.95rem;
            line-height: 1.5;
            word-wrap: break-word;
        }
        
        .ai-bubble.user {
            background-color: var(--fi-color-primary-500, #f59e0b);
            color: white;
            border-bottom-right-radius: 0.25rem;
        }
        
        .ai-bubble.assistant {
            background-color: white;
            color: rgb(31, 41, 55);
            border-bottom-left-radius: 0.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .dark .ai-bubble.assistant {
            background-color: rgba(255, 255, 255, 0.05);
            color: rgb(243, 244, 246);
            box-shadow: none;
        }

        .ai-bubble.system {
            background-color: rgb(254, 226, 226);
            color: rgb(153, 27, 27);
        }
        .dark .ai-bubble.system {
            background-color: rgba(239, 68, 68, 0.1);
            color: rgb(248, 113, 113);
        }

        .ai-msg-time {
            font-size: 0.65rem;
            margin-top: 0.5rem;
            opacity: 0.7;
        }
        .ai-msg-row.user .ai-msg-time { text-align: right; }
        .ai-msg-row.assistant .ai-msg-time { text-align: left; }

        /* Input Area */
        .ai-input-area {
            padding: 1rem 1.5rem;
            background: transparent;
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        .dark .ai-input-area { border-color: rgba(255,255,255,0.05); }

        .ai-input-pill {
            background-color: white;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 1.5rem;
            padding: 0.5rem 0.5rem 0.5rem 1rem;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }
        .ai-input-pill:focus-within {
            border-color: var(--fi-color-primary-500, #f59e0b);
            box-shadow: 0 0 0 1px var(--fi-color-primary-500, #f59e0b);
        }
        .dark .ai-input-pill {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255,255,255,0.1);
        }

        .ai-textarea {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            resize: none;
            padding: 0.25rem 0;
            color: rgb(17, 24, 39);
            font-size: 0.95rem;
            max-height: 120px;
        }
        .dark .ai-textarea { color: white; }
        .ai-textarea::placeholder { color: rgb(156, 163, 175); }

        .ai-send-btn {
            background-color: var(--fi-color-primary-500, #f59e0b);
            color: white;
            border: none;
            border-radius: 50%;
            width: 2.25rem;
            height: 2.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.1s;
            margin-left: 0.5rem;
            flex-shrink: 0;
        }
        .ai-send-btn:hover:not(:disabled) {
            transform: scale(1.05);
            background-color: var(--fi-color-primary-600, #d97706);
        }
        .ai-send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .ai-stop-btn { background-color: rgb(239, 68, 68); }
        .ai-stop-btn:hover:not(:disabled) { background-color: rgb(220, 38, 38); }

        /* Loading Dots */
        .ai-dots { display: flex; align-items: center; gap: 0.3rem; height: 1.5rem; }
        .ai-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background-color: var(--fi-color-primary-500, #f59e0b);
            animation: pulse-dot 1.4s infinite ease-in-out both;
        }
        .ai-dot:nth-child(1) { animation-delay: -0.32s; }
        .ai-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes pulse-dot { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }

        /* Empty State */
        .ai-empty {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            height: 100%; text-align: center; gap: 1rem;
        }
        .ai-empty-icon {
            background-color: rgba(245, 158, 11, 0.1); color: var(--fi-color-primary-600, #d97706);
            padding: 1rem; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        }
        .ai-empty-text { color: rgb(107, 114, 128); max-width: 280px; font-size: 0.95rem; line-height: 1.5; }
        .dark .ai-empty-text { color: rgb(156, 163, 175); }

        /* Markdown Spacing */
        .ai-bubble.assistant p { margin-bottom: 0.5rem; margin-top: 0; }
        .ai-bubble.assistant p:last-child { margin-bottom: 0; }
        .ai-bubble.assistant ul { margin-left: 1rem; margin-bottom: 0.5rem; }
        .ai-bubble.assistant li { list-style-type: disc; }
    </style>

    <!-- Floating Action Button -->
    <button class="ai-fab" wire:click="toggleCopilot" aria-label="Toggle AI Copilot">
        @if($isOpen)
            <x-heroicon-o-x-mark style="width: 1.5rem; height: 1.5rem; color: white;" />
        @else
            <img src="{{ asset('dmario.jpeg') }}" alt="AI Copilot" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" />
        @endif
    </button>

    <!-- Backdrop -->
    <div class="ai-backdrop {{ $isOpen ? 'is-open' : '' }}" wire:click="toggleCopilot"></div>

    <!-- Sidebar Overlay -->
    <div class="ai-sidebar {{ $isOpen ? 'is-open' : '' }}" x-data="{
        init() {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && @js($isOpen)) {
                    @this.toggleCopilot();
                }
            });
        }
    }">
        <!-- Sidebar Header -->
        <div class="ai-sidebar-header">
            <div class="ai-sidebar-title">
                <x-heroicon-o-sparkles style="width: 1.25rem; height: 1.25rem; color: var(--fi-color-primary-600, #d97706);" />
                Dmario AI Copilot
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button class="ai-close-btn" wire:click="startNewChat" title="New Chat">
                    <x-heroicon-o-chat-bubble-bottom-center-text style="width: 1.25rem; height: 1.25rem;" />
                </button>
                <button class="ai-close-btn" wire:click="toggleCopilot" title="Close">
                    <x-heroicon-m-x-mark style="width: 1.25rem; height: 1.25rem;" />
                </button>
            </div>
        </div>
        
        <!-- Messages Container -->
        <div class="ai-chat-messages" id="ai-chat-container" wire:poll.5s="checkForNewMessages">
            @if(empty($messages) && !$isStreaming)
                <div class="ai-empty">
                    <div class="ai-empty-icon">
                        <x-heroicon-o-chat-bubble-left-right style="width: 2rem; height: 2rem;" />
                    </div>
                    <p class="ai-empty-text">
                        <strong>Hi there!</strong> I'm your Dmario AI Copilot.<br>Ask me about sales, reservations, or menu performance.
                    </p>
                </div>
            @else
                @foreach($messages as $message)
                    <div class="ai-msg-row {{ $message['role'] === 'user' ? 'user' : 'assistant' }}">
                        <div class="ai-bubble {{ $message['role'] === 'system' ? 'system' : ($message['role'] === 'user' ? 'user' : 'assistant') }}">
                            
                            @if($message['role'] === 'assistant')
                                <div class="prose dark:prose-invert max-w-none">
                                    {!! Str::markdown($message['content']) !!}
                                </div>
                            @else
                                <div style="white-space: pre-wrap;">{{ $message['content'] }}</div>
                            @endif
                            
                            <div class="ai-msg-time">
                                {{ \Carbon\Carbon::parse($message['timestamp'])->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            
            <!-- Active Streaming Response -->
            @if($isStreaming)
                <div class="ai-msg-row assistant">
                    <div class="ai-bubble assistant">
                        <div class="prose dark:prose-invert max-w-none" wire:stream="streamedContent">
                            @if($streamedContent)
                                {!! Str::markdown($streamedContent) !!}
                            @else
                                <div class="ai-dots">
                                    <div class="ai-dot"></div>
                                    <div class="ai-dot"></div>
                                    <div class="ai-dot"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Bottom Section: Input Area -->
        <div class="ai-input-area">
            <form wire:submit.prevent="sendMessage" style="position: relative;">
                <div class="ai-input-pill">
                    <textarea 
                        wire:model="prompt"
                        wire:keydown.enter.exact.prevent="sendMessage"
                        placeholder="Message AI Copilot..."
                        class="ai-textarea"
                        rows="1"
                        oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 120) + 'px'"
                        @disabled($isSending)
                    ></textarea>
                    
                    @if($isStreaming)
                        <button 
                            type="button" 
                            class="ai-send-btn ai-stop-btn"
                            wire:click="cancelStream"
                            title="Stop generating"
                        >
                            <x-heroicon-s-stop style="width: 1rem; height: 1rem;" />
                        </button>
                    @else
                        <button 
                            type="submit" 
                            class="ai-send-btn"
                            wire:loading.attr="disabled"
                            wire:target="sendMessage"
                            @disabled($isSending)
                        >
                            <span wire:loading.remove wire:target="sendMessage" style="line-height: 0;">
                                <x-heroicon-s-arrow-up style="width: 1rem; height: 1rem;" />
                            </span>
                            <span wire:loading wire:target="sendMessage" style="line-height: 0;">
                                <x-heroicon-o-arrow-path style="width: 1rem; height: 1rem; animation: spin 1s linear infinite;" />
                            </span>
                        </button>
                    @endif
                </div>
            </form>

            <div style="font-size: 0.65rem; color: #9ca3af; text-align: center; margin-top: 0.5rem;">
                AI can make mistakes. Check important info. ({{ strlen($prompt) }}/1000)
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        const container = document.getElementById('ai-chat-container');
        if (!container) return;

        let autoScrollEnabled = true;

        const scrollToBottom = (behavior = 'smooth') => {
            if (autoScrollEnabled && container) {
                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: behavior
                });
            }
        };

        // 1. Detect manual scrolling
        container.addEventListener('scroll', () => {
            // Margin of error of 15px for zoom/scaling differences
            const isAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 15;
            
            if (isAtBottom) {
                // User scrolled to bottom manually, re-enable auto-scroll
                autoScrollEnabled = true;
            } else {
                // User scrolled up, pause auto-scroll
                autoScrollEnabled = false;
            }
        });

        // 2. Observe DOM changes for streaming updates
        // This captures wire:stream text node injections without polling
        const observer = new MutationObserver((mutations) => {
            let shouldScroll = false;
            for (let mutation of mutations) {
                if (mutation.type === 'childList' || mutation.type === 'characterData') {
                    shouldScroll = true;
                    break;
                }
            }
            if (shouldScroll) {
                scrollToBottom('smooth');
            }
        });

        observer.observe(container, {
            childList: true,
            subtree: true,
            characterData: true
        });

        // 3. User intentional actions (Sending a message forces scroll)
        const form = container.parentElement.querySelector('form');
        if (form) {
            form.addEventListener('submit', () => {
                autoScrollEnabled = true;
                setTimeout(() => scrollToBottom('smooth'), 50);
            });
        }
        
        const textarea = container.parentElement.querySelector('textarea');
        if (textarea) {
            textarea.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    autoScrollEnabled = true;
                    setTimeout(() => scrollToBottom('smooth'), 50);
                }
            });
        }

        // 4. Livewire specific events (Standard morphs)
        Livewire.hook('morph.updated', ({ el, component }) => {
            if (component.name === 'ai-copilot') {
                scrollToBottom('smooth');
            }
        });

        window.addEventListener('ai-copilot-opened', () => {
            autoScrollEnabled = true;
            setTimeout(() => scrollToBottom('auto'), 50); // instant jump when opening
        });

        window.addEventListener('ai-copilot-messages-updated', () => {
            autoScrollEnabled = true;
            setTimeout(() => scrollToBottom('smooth'), 50);
        });
    });
</script>
