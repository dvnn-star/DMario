<?php

namespace App\AI\Streaming\Buffer;

/**
 * Batches incoming tokens before flushing to the UI.
 * Reduces Livewire round-trips from hundreds (one per token) down to ~20-40 batched updates.
 */
class StreamBuffer
{
    protected string $buffer = '';
    protected float $lastFlushTime;
    protected int $flushIntervalMs;
    protected int $maxBufferChars;

    /**
     * @param int $flushIntervalMs Minimum milliseconds between flushes (default 50ms)
     * @param int $maxBufferChars Force flush if buffer exceeds this character count
     */
    public function __construct(int $flushIntervalMs = 50, int $maxBufferChars = 20)
    {
        $this->flushIntervalMs = $flushIntervalMs;
        $this->maxBufferChars = $maxBufferChars;
        $this->lastFlushTime = microtime(true) * 1000;
    }

    /**
     * Append a token to the internal buffer.
     */
    public function append(string $token): void
    {
        $this->buffer .= $token;
    }

    /**
     * Whether the buffer should be flushed based on time or size constraints.
     */
    public function shouldFlush(): bool
    {
        if ($this->buffer === '') {
            return false;
        }

        $elapsed = (microtime(true) * 1000) - $this->lastFlushTime;

        return $elapsed >= $this->flushIntervalMs || strlen($this->buffer) >= $this->maxBufferChars;
    }

    /**
     * Return the buffered content and clear it.
     */
    public function flush(): string
    {
        $content = $this->buffer;
        $this->buffer = '';
        $this->lastFlushTime = microtime(true) * 1000;
        return $content;
    }

    /**
     * Force flush regardless of timing (used on stream completion).
     */
    public function forceFlush(): string
    {
        $content = $this->buffer;
        $this->buffer = '';
        return $content;
    }

    /**
     * Clear internal state completely.
     */
    public function reset(): void
    {
        $this->buffer = '';
        $this->lastFlushTime = microtime(true) * 1000;
    }

    /**
     * Check if the buffer has any content.
     */
    public function hasContent(): bool
    {
        return $this->buffer !== '';
    }
}
