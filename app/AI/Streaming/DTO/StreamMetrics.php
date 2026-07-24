<?php

namespace App\AI\Streaming\DTO;

/**
 * Immutable observability container for stream performance metrics.
 */
readonly class StreamMetrics
{
    /**
     * @param float $latencyMs Time from request sent to first byte
     * @param float $ttftMs Time To First Token
     * @param float $totalStreamMs Total stream duration
     * @param int $promptTokens Prompt token count
     * @param int $completionTokens Completion token count
     * @param int $chunkCount Total chunks received
     * @param bool $wasCancelled Whether the user cancelled
     * @param string $provider Provider name (e.g., 'groq')
     * @param int $retryCount Number of retries attempted
     */
    public function __construct(
        public float $latencyMs = 0,
        public float $ttftMs = 0,
        public float $totalStreamMs = 0,
        public int $promptTokens = 0,
        public int $completionTokens = 0,
        public int $chunkCount = 0,
        public bool $wasCancelled = false,
        public string $provider = '',
        public int $retryCount = 0
    ) {
    }

    public function toArray(): array
    {
        return [
            'latency_ms' => $this->latencyMs,
            'ttft_ms' => $this->ttftMs,
            'total_stream_ms' => $this->totalStreamMs,
            'prompt_tokens' => $this->promptTokens,
            'completion_tokens' => $this->completionTokens,
            'chunk_count' => $this->chunkCount,
            'was_cancelled' => $this->wasCancelled,
            'provider' => $this->provider,
            'retry_count' => $this->retryCount,
        ];
    }
}
