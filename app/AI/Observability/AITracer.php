<?php

namespace App\AI\Observability;

use App\AI\Observability\Contracts\CostCalculator;
use App\AI\Observability\Contracts\MetricsStore;
use App\AI\Observability\DTO\AITrace;
use App\AI\Observability\DTO\RequestMetrics;
use App\AI\Observability\DTO\ToolMetrics;
use App\AI\Observability\DTO\TraceSpan;
use App\AI\Observability\Events\AIRequestCompleted;
use App\AI\Observability\Events\AIRequestFailed;
use App\AI\Observability\Events\AIRequestStarted;

/**
 * Central tracer for the AI pipeline.
 * The Orchestrator calls this service at each lifecycle stage.
 * It assembles structured trace data and delegates persistence to MetricsStore.
 */
class AITracer
{
    protected ?AITrace $currentTrace = null;

    /** @var string[] Keys to strip from span metadata */
    protected array $sensitivePatterns = ['key', 'secret', 'password', 'credential', 'authorization'];

    public function __construct(
        protected MetricsStore $store,
        protected CostCalculator $costCalculator
    ) {
    }

    /**
     * Begin a new trace for an AI request.
     */
    public function startTrace(string $sessionId, ?int $userId, string $provider, string $model): AITrace
    {
        $this->currentTrace = AITrace::start($sessionId, $userId, $provider, $model);

        if (config('ai.observability.enabled', true)) {
            event(new AIRequestStarted($this->currentTrace->traceId, $sessionId, $provider, $model));
        }

        return $this->currentTrace;
    }

    /**
     * Open a timing span for a specific pipeline stage.
     */
    public function startSpan(string $name, array $metadata = []): TraceSpan
    {
        $sanitizedMetadata = $this->sanitizeMetadata($metadata);

        $span = new TraceSpan(
            name: $name,
            startedAt: microtime(true),
            metadata: $sanitizedMetadata
        );

        return $span;
    }

    /**
     * Close a span and attach it to the current trace.
     */
    public function endSpan(TraceSpan $span, string $status = 'ok', ?string $error = null): void
    {
        $span->finish($status, $error);

        $this->currentTrace?->addSpan($span);
    }

    /**
     * Record tool execution metrics.
     */
    public function recordToolExecution(ToolMetrics $tool): void
    {
        $this->currentTrace?->addToolMetrics($tool);
    }

    /**
     * Record aggregated request metrics.
     */
    public function recordMetrics(RequestMetrics $metrics): void
    {
        $this->currentTrace?->setMetrics($metrics);
    }

    /**
     * Finalize the trace and persist it via the MetricsStore.
     */
    public function finishTrace(string $status = 'completed'): ?AITrace
    {
        if (!$this->currentTrace) {
            return null;
        }

        $this->currentTrace->finish($status);

        if (config('ai.observability.enabled', true)) {
            $this->store->store($this->currentTrace);

            if ($status === 'completed') {
                event(new AIRequestCompleted(
                    $this->currentTrace->traceId,
                    $this->currentTrace->getTotalDurationMs()
                ));
            } else {
                event(new AIRequestFailed(
                    $this->currentTrace->traceId,
                    $status
                ));
            }
        }

        $trace = $this->currentTrace;
        $this->currentTrace = null;

        return $trace;
    }

    /**
     * Get the current active trace (for debug panel injection).
     */
    public function getCurrentTrace(): ?AITrace
    {
        return $this->currentTrace;
    }

    /**
     * Strip sensitive keys from metadata before storage.
     */
    protected function sanitizeMetadata(array $metadata): array
    {
        $sanitized = [];

        foreach ($metadata as $key => $value) {
            $lowerKey = strtolower($key);
            $isSensitive = false;

            foreach ($this->sensitivePatterns as $pattern) {
                // Allow 'prompt_tokens' and 'completion_tokens' through
                if ($pattern === 'key' && str_contains($lowerKey, 'token')) {
                    continue;
                }

                if (str_contains($lowerKey, $pattern)) {
                    $isSensitive = true;
                    break;
                }
            }

            $sanitized[$key] = $isSensitive ? '***REDACTED***' : $value;
        }

        return $sanitized;
    }
}
