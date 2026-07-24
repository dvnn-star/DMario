<?php

namespace App\AI\Streaming\Parsers;

use App\AI\Streaming\DTO\StreamChunk;

/**
 * Contract for parsing raw SSE/HTTP lines into StreamChunk DTOs.
 * Each provider format gets its own parser implementation.
 */
interface ChunkParser
{
    /**
     * Parse a single raw SSE line into a StreamChunk.
     *
     * @param string $rawLine The raw line from the HTTP stream
     * @param string $accumulatedContent The full text accumulated so far
     * @param int $index Current chunk index
     * @return StreamChunk|null Null if the line is empty, a comment, or unparseable
     */
    public function parse(string $rawLine, string $accumulatedContent, int $index): ?StreamChunk;

    /**
     * Check if this line signals the end of the stream.
     */
    public function isDone(string $rawLine): bool;
}
