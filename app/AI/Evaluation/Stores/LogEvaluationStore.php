<?php

namespace App\AI\Evaluation\Stores;

use App\AI\Evaluation\Contracts\EvaluationStore;
use App\AI\Evaluation\DTO\EvaluationReport;
use Illuminate\Support\Facades\Log;

/**
 * Default implementation for storing evaluation reports in Laravel's log system.
 */
class LogEvaluationStore implements EvaluationStore
{
    public function store(EvaluationReport $report): void
    {
        $channel = config('ai.evaluation.log_channel', 'ai_eval');

        try {
            Log::channel($channel)->info('AI Evaluation Report', $report->toArray());
        } catch (\InvalidArgumentException $e) {
            // Fall back to default log channel if custom channel isn't configured
            Log::info('AI Evaluation Report', $report->toArray());
        }
    }

    public function get(string $traceId): ?EvaluationReport
    {
        // Log-based storage is write-only.
        // For read access, implement DatabaseEvaluationStore.
        return null;
    }
}
