<?php

namespace App\AI\Orchestrator\DTO;

use App\AI\Streaming\DTO\StreamResult;

/**
 * Wraps the full orchestrator pipeline output.
 */
readonly class OrchestratorResult
{
    public function __construct(
        public StreamResult $streamResult,
        public ?Intent $intent = null,
        public ?ToolContext $toolContext = null,
    ) {
    }

    public function wasCancelled(): bool
    {
        return $this->streamResult->wasCancelled();
    }
}
