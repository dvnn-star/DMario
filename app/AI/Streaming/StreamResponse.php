<?php

namespace App\AI\Streaming;

use App\AI\Streaming\DTO\StreamChunk;
use App\AI\Streaming\DTO\StreamMetrics;
use App\AI\Streaming\Parsers\ChunkParser;
use Generator;
use Psr\Http\Message\StreamInterface;

/**
 * Provider-agnostic iterable wrapper around a raw HTTP stream.
 * Yields parsed StreamChunk DTOs, hiding all transport-level details
 * from the Orchestrator and UI layers.
 */
class StreamResponse
{
    protected bool $cancelled = false;
    protected float $startTime;
    protected float $firstTokenTime = 0;
    protected int $chunkCount = 0;
    protected string $accumulatedContent = '';
    protected int $promptTokens = 0;
    protected int $completionTokens = 0;
    protected string $provider;

    public function __construct(
        protected StreamInterface $httpStream,
        protected ChunkParser $parser,
        string $provider = 'groq'
    ) {
        $this->startTime = microtime(true) * 1000;
        $this->provider = $provider;
    }

    /**
     * Yields parsed StreamChunk DTOs one at a time.
     *
     * @return Generator<StreamChunk>
     */
    public function chunks(): Generator
    {
        $buffer = '';

        while (!$this->httpStream->eof() && !$this->cancelled) {
            $byte = $this->httpStream->read(1);

            if ($byte === false || $byte === '') {
                break;
            }

            $buffer .= $byte;

            // SSE lines are delimited by newlines
            if ($byte !== "\n") {
                continue;
            }

            $line = $buffer;
            $buffer = '';

            // Check for end-of-stream sentinel
            if ($this->parser->isDone($line)) {
                break;
            }

            $chunk = $this->parser->parse($line, $this->accumulatedContent, $this->chunkCount);

            if ($chunk === null) {
                continue;
            }

            // Track first token timing
            if ($this->firstTokenTime === 0.0 && $chunk->token !== '') {
                $this->firstTokenTime = microtime(true) * 1000;
            }

            $this->accumulatedContent = $chunk->accumulatedContent;
            $this->chunkCount++;

            yield $chunk;

            // If the provider signals completion via finish_reason
            if ($chunk->isComplete()) {
                break;
            }
        }
    }

    /**
     * Cancel the stream by closing the underlying HTTP connection.
     */
    public function cancel(): void
    {
        $this->cancelled = true;
        $this->httpStream->close();
    }

    public function isCancelled(): bool
    {
        return $this->cancelled;
    }

    /**
     * Build the observability metrics snapshot.
     */
    public function getMetrics(): StreamMetrics
    {
        $now = microtime(true) * 1000;

        return new StreamMetrics(
            latencyMs: $this->firstTokenTime > 0 ? $this->firstTokenTime - $this->startTime : $now - $this->startTime,
            ttftMs: $this->firstTokenTime > 0 ? $this->firstTokenTime - $this->startTime : 0,
            totalStreamMs: $now - $this->startTime,
            promptTokens: $this->promptTokens,
            completionTokens: $this->completionTokens,
            chunkCount: $this->chunkCount,
            wasCancelled: $this->cancelled,
            provider: $this->provider
        );
    }

    /**
     * Get the full accumulated content so far.
     */
    public function getAccumulatedContent(): string
    {
        return $this->accumulatedContent;
    }
}
