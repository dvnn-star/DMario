<?php

namespace App\AI\Streaming\DTO;

/**
 * Immutable final result produced after a stream completes.
 * Equivalent to AIResponse but with streaming metadata.
 */
readonly class StreamResult
{
    /**
     * @param string $content The full accumulated response text
     * @param string $model Model used
     * @param string $finishReason 'stop', 'tool_calls', or 'cancelled'
     * @param StreamMetrics $metrics Observability data
     * @param int|null $promptTokens From the final chunk's usage field
     * @param int|null $completionTokens From the final chunk's usage field
     * @param array $toolCalls Any tool calls extracted
     */
    public function __construct(
        public string $content,
        public string $model,
        public string $finishReason = 'stop',
        public StreamMetrics $metrics = new StreamMetrics(),
        public ?int $promptTokens = null,
        public ?int $completionTokens = null,
        public array $toolCalls = []
    ) {
    }

    public function wasCancelled(): bool
    {
        return $this->finishReason === 'cancelled';
    }

    public function hasToolCalls(): bool
    {
        return !empty($this->toolCalls);
    }
}
