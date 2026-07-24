<?php

namespace App\AI\Observability\DTO;

/**
 * Per-tool execution metrics.
 */
readonly class ToolMetrics
{
    public function __construct(
        public string $toolName,
        public float $executionTimeMs = 0,
        public int $inputSizeBytes = 0,
        public int $outputSizeBytes = 0,
        public bool $success = true,
        public ?string $error = null,
        public int $retryCount = 0
    ) {
    }

    public function toArray(): array
    {
        return [
            'tool_name' => $this->toolName,
            'execution_time_ms' => $this->executionTimeMs,
            'input_size_bytes' => $this->inputSizeBytes,
            'output_size_bytes' => $this->outputSizeBytes,
            'success' => $this->success,
            'error' => $this->error,
            'retry_count' => $this->retryCount,
        ];
    }
}
