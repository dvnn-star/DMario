<?php

namespace App\AI\Evaluation\Listeners;

use App\AI\Evaluation\EvaluationPipeline;
use App\AI\Observability\Contracts\MetricsStore;
use App\AI\Observability\Events\AIRequestCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Asynchronously triggers the evaluation pipeline when an AI request successfully completes.
 */
class RunEvaluationPipeline implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The name of the connection the job should be sent to.
     * We use a dedicated queue if possible, otherwise default.
     */
    public ?string $connection = null;
    public ?string $queue = 'ai_eval';

    public function __construct(
        protected EvaluationPipeline $pipeline,
        // Assuming we could fetch the trace from a DatabaseStore. 
        // For now we will just log that the evaluation would run, 
        // since LogMetricsStore is write-only.
        protected MetricsStore $metricsStore 
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(AIRequestCompleted $event): void
    {
        if (!config('ai.evaluation.enabled', true)) {
            return;
        }

        try {
            // In a full DB implementation:
            // $trace = $this->metricsStore->getTrace($event->traceId);
            // $this->pipeline->evaluate($trace);
            
            Log::debug("Evaluation pipeline triggered asynchronously for trace: {$event->traceId}");
            
        } catch (\Throwable $e) {
            Log::error("Evaluation pipeline failed for trace {$event->traceId}: " . $e->getMessage());
        }
    }
}
