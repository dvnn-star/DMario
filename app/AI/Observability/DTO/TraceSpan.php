<?php

namespace App\AI\Observability\DTO;

/**
 * A single measurable stage within an AI request trace.
 */
class TraceSpan
{
    public ?float $finishedAt = null;
    public ?float $durationMs = null;
    public string $status = 'ok';
    public ?string $error = null;

    public function __construct(
        public readonly string $name,
        public readonly float $startedAt,
        public readonly array $metadata = []
    ) {
    }

    /**
     * Close this span and compute its duration.
     */
    public function finish(string $status = 'ok', ?string $error = null): void
    {
        $this->finishedAt = microtime(true);
        $this->durationMs = round(($this->finishedAt - $this->startedAt) * 1000, 2);
        $this->status = $status;
        $this->error = $error;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'duration_ms' => $this->durationMs,
            'status' => $this->status,
            'error' => $this->error,
            'metadata' => $this->metadata,
        ];
    }
}
