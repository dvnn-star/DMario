<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TriggerProactiveAgent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param string $sessionId The session ID or user ID to attach the insight to.
     * @param string $context The context or prompt for the AI to reason about (e.g., "Analyze today's revenue").
     */
    public function __construct(
        public string $sessionId,
        public string $context
    ) {
    }
}
