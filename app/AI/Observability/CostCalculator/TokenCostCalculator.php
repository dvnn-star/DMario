<?php

namespace App\AI\Observability\CostCalculator;

use App\AI\Observability\Contracts\CostCalculator;

/**
 * Calculates estimated USD cost based on token usage and configurable pricing.
 * Pricing is defined per provider and model in config/ai.php.
 */
class TokenCostCalculator implements CostCalculator
{
    public function estimate(string $provider, string $model, int $promptTokens, int $completionTokens): float
    {
        $pricing = config("ai.pricing.{$provider}.{$model}");

        if (!$pricing) {
            return 0.0;
        }

        // Pricing is per 1 million tokens
        $promptCost = ($promptTokens / 1_000_000) * ($pricing['prompt'] ?? 0);
        $completionCost = ($completionTokens / 1_000_000) * ($pricing['completion'] ?? 0);

        return round($promptCost + $completionCost, 6);
    }
}
