<?php

namespace App\AI\Observability\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AIRequestCompleted
{
    use Dispatchable;

    public function __construct(
        public readonly string $traceId,
        public readonly float $totalDurationMs
    ) {
    }
}
