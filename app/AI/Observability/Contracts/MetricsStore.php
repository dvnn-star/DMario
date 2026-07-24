<?php

namespace App\AI\Observability\Contracts;

use App\AI\Observability\DTO\AITrace;

/**
 * Storage abstraction for AI observability data.
 * Implementations can write to log files, databases, OpenTelemetry, LangFuse, etc.
 */
interface MetricsStore
{
    /**
     * Persist a completed trace with all its spans and metrics.
     */
    public function store(AITrace $trace): void;
}
