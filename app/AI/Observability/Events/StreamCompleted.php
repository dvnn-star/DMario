<?php

namespace App\AI\Observability\Events;

use Illuminate\Foundation\Events\Dispatchable;

class StreamCompleted
{
    use Dispatchable;

    public function __construct(
        public readonly string $traceId,
        public readonly float $streamDurationMs,
        public readonly float $ttftMs,
        public readonly int $chunkCount,
        public readonly bool $wasCancelled
    ) {
    }
}
