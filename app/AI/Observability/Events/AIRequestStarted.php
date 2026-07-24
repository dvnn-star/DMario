<?php

namespace App\AI\Observability\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AIRequestStarted
{
    use Dispatchable;

    public function __construct(
        public readonly string $traceId,
        public readonly string $sessionId,
        public readonly string $provider,
        public readonly string $model
    ) {
    }
}
