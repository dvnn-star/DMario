<?php

namespace App\AI\Observability\DTO;

/**
 * Aggregated metrics for a complete AI request.
 */
readonly class RequestMetrics
{
    public function __construct(
        public int $promptTokens = 0,
        public int $completionTokens = 0,
        public int $totalTokens = 0,
        public float $estimatedCostUsd = 0,
        public float $totalLatencyMs = 0,
        public float $ttftMs = 0,
        public float $streamDurationMs = 0,
        public int $contextSizeBytes = 0,
        public int $promptSizeBytes = 0,
        public int $toolCount = 0,
        public int $retryCount = 0,
        public string $guardrailResult = 'passed',
        public string $finishReason = 'stop',
        public string $promptVersion = 'general'
    ) {
    }

    public function toArray(): array
    {
        return [
            'prompt_tokens' => $this->promptTokens,
            'completion_tokens' => $this->completionTokens,
            'total_tokens' => $this->totalTokens,
            'estimated_cost_usd' => $this->estimatedCostUsd,
            'total_latency_ms' => $this->totalLatencyMs,
            'ttft_ms' => $this->ttftMs,
            'stream_duration_ms' => $this->streamDurationMs,
            'context_size_bytes' => $this->contextSizeBytes,
            'prompt_size_bytes' => $this->promptSizeBytes,
            'tool_count' => $this->toolCount,
            'retry_count' => $this->retryCount,
            'guardrail_result' => $this->guardrailResult,
            'finish_reason' => $this->finishReason,
            'prompt_version' => $this->promptVersion,
        ];
    }
}
