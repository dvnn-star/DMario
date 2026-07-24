<?php

namespace App\AI\Streaming\Parsers;

use App\AI\Streaming\DTO\StreamChunk;

/**
 * Parses the standard OpenAI-compatible SSE format used by Groq, OpenAI,
 * and most LLM providers.
 *
 * Expected format:
 *   data: {"choices":[{"delta":{"content":"token"},"finish_reason":null}]}
 *   data: [DONE]
 */
class SSEChunkParser implements ChunkParser
{
    public function parse(string $rawLine, string $accumulatedContent, int $index): ?StreamChunk
    {
        $rawLine = trim($rawLine);

        // Skip empty lines and comments
        if ($rawLine === '' || str_starts_with($rawLine, ':')) {
            return null;
        }

        // Strip the "data: " prefix
        if (!str_starts_with($rawLine, 'data: ')) {
            return null;
        }

        $jsonString = substr($rawLine, 6);

        // Check for the [DONE] sentinel
        if ($jsonString === '[DONE]') {
            return null;
        }

        $data = json_decode($jsonString, true);

        if (!$data || !isset($data['choices'][0])) {
            return null;
        }

        $choice = $data['choices'][0];
        $delta = $choice['delta'] ?? [];
        $token = $delta['content'] ?? '';
        $finishReason = $choice['finish_reason'] ?? null;
        $toolCalls = $delta['tool_calls'] ?? [];

        // Accumulate content
        $newAccumulated = $accumulatedContent . $token;

        return new StreamChunk(
            token: $token,
            accumulatedContent: $newAccumulated,
            finishReason: $finishReason,
            toolCalls: $toolCalls,
            index: $index,
            timestamp: now()->toIso8601String()
        );
    }

    public function isDone(string $rawLine): bool
    {
        return trim($rawLine) === 'data: [DONE]';
    }
}
