<?php

namespace App\Jobs;

use App\AI\Orchestrator\AIOrchestrator;
use App\Events\TriggerProactiveAgent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunProactiveAgentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected TriggerProactiveAgent $event
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(AIOrchestrator $orchestrator): void
    {
        Log::info("Running Proactive Agent for session: {$this->event->sessionId}");

        try {
            // Provide a proactive system directive alongside the context
            $proactivePrompt = "PROACTIVE SYSTEM TRIGGER: " . $this->event->context;

            // Run the ReAct loop
            $result = $orchestrator->orchestrate(
                userText: $proactivePrompt,
                sessionId: $this->event->sessionId,
                onFlush: function(string $chunk) {
                    // In a background job, we don't care about streaming chunks to the console,
                    // we just let the Orchestrator finish reasoning.
                }
            );

            Log::info("Proactive Agent completed successfully for session: {$this->event->sessionId}");
        } catch (\Throwable $e) {
            Log::error("Proactive Agent failed: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
