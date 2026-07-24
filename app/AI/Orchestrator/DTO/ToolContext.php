<?php

namespace App\AI\Orchestrator\DTO;

/**
 * Wraps the result of one or more tool executions.
 */
readonly class ToolContext
{
    /**
     * @param array<string, mixed> $results  Map of tool name → execution result
     * @param string $formattedText          Human-readable context string for the LLM
     */
    public function __construct(
        public array $results,
        public string $formattedText,
    ) {
    }

    public function hasData(): bool
    {
        return !empty($this->results);
    }
}
