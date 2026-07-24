<?php

namespace App\AI\Streaming\DTO;

/**
 * Immutable representation of a single parsed chunk from the AI provider stream.
 */
readonly class StreamChunk
{
    /**
     * @param string $token The text fragment received in this chunk
     * @param string $accumulatedContent Full text assembled so far
     * @param string|null $finishReason null while streaming, 'stop' or 'tool_calls' when done
     * @param array $toolCalls Tool call payloads if finishReason is 'tool_calls'
     * @param int $index Chunk sequence number
     * @param string $timestamp ISO8601 when this chunk was received
     */
    public function __construct(
        public string $token,
        public string $accumulatedContent,
        public ?string $finishReason = null,
        public array $toolCalls = [],
        public int $index = 0,
        public string $timestamp = ''
    ) {
    }

    public function isComplete(): bool
    {
        return $this->finishReason !== null;
    }

    public function hasToolCalls(): bool
    {
        return !empty($this->toolCalls);
    }
}
