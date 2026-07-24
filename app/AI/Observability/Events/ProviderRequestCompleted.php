<?php

namespace App\AI\Observability\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ProviderRequestCompleted
{
    use Dispatchable;

    public function __construct(
        public readonly string $traceId,
        public readonly string $provider,
        public readonly float $durationMs,
        public readonly int $promptTokens,
        public readonly int $completionTokens
    ) {
    }
}
