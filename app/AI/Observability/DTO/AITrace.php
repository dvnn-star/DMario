<?php

namespace App\AI\Observability\DTO;

use Illuminate\Support\Str;

/**
 * Master container representing one complete AI request lifecycle.
 */
class AITrace
{
    public ?float $finishedAt = null;
    public string $status = 'pending';

    /** @var TraceSpan[] */
    protected array $spans = [];

    /** @var ToolMetrics[] */
    protected array $toolMetrics = [];

    protected ?RequestMetrics $metrics = null;

    public function __construct(
        public readonly string $traceId,
        public readonly string $sessionId,
        public readonly ?int $userId,
        public readonly string $provider,
        public readonly string $model,
        public readonly float $startedAt
    ) {
    }

    /**
     * Create a new trace with auto-generated IDs.
     */
    public static function start(string $sessionId, ?int $userId, string $provider, string $model): self
    {
        return new self(
            traceId: (string) Str::uuid(),
            sessionId: $sessionId,
            userId: $userId,
            provider: $provider,
            model: $model,
            startedAt: microtime(true)
        );
    }

    public function addSpan(TraceSpan $span): void
    {
        $this->spans[] = $span;
    }

    public function addToolMetrics(ToolMetrics $tool): void
    {
        $this->toolMetrics[] = $tool;
    }

    public function setMetrics(RequestMetrics $metrics): void
    {
        $this->metrics = $metrics;
    }

    public function finish(string $status = 'completed'): void
    {
        $this->finishedAt = microtime(true);
        $this->status = $status;
    }

    public function getSpans(): array
    {
        return $this->spans;
    }

    public function getMetrics(): ?RequestMetrics
    {
        return $this->metrics;
    }

    public function getToolMetrics(): array
    {
        return $this->toolMetrics;
    }

    public function getTotalDurationMs(): float
    {
        $end = $this->finishedAt ?? microtime(true);
        return round(($end - $this->startedAt) * 1000, 2);
    }

    public function toArray(): array
    {
        return [
            'trace_id' => $this->traceId,
            'session_id' => $this->sessionId,
            'user_id' => $this->userId,
            'provider' => $this->provider,
            'model' => $this->model,
            'status' => $this->status,
            'total_duration_ms' => $this->getTotalDurationMs(),
            'metrics' => $this->metrics?->toArray(),
            'spans' => array_map(fn(TraceSpan $s) => $s->toArray(), $this->spans),
            'tools' => array_map(fn(ToolMetrics $t) => $t->toArray(), $this->toolMetrics),
            'started_at' => date('c', (int) $this->startedAt),
            'finished_at' => $this->finishedAt ? date('c', (int) $this->finishedAt) : null,
        ];
    }
}
