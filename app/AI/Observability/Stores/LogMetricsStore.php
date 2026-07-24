<?php

namespace App\AI\Observability\Stores;

use App\AI\Observability\Contracts\MetricsStore;
use App\AI\Observability\DTO\AITrace;
use Illuminate\Support\Facades\Log;

/**
 * Writes structured JSON traces to Laravel's logging system.
 * Uses a dedicated 'ai' channel when available, falls back to default.
 */
class LogMetricsStore implements MetricsStore
{
    public function store(AITrace $trace): void
    {
        $channel = config('ai.observability.log_channel', 'ai');

        try {
            Log::channel($channel)->info('AI Request Trace', $trace->toArray());
        } catch (\InvalidArgumentException $e) {
            // Fall back to default channel if 'ai' channel is not configured
            Log::info('AI Request Trace', $trace->toArray());
        }
    }
}
