<?php

namespace App\AI\Observability\Contracts;

/**
 * Interface for estimating the dollar cost of an AI request based on token usage.
 */
interface CostCalculator
{
    /**
     * Estimate the USD cost for the given provider, model, and token counts.
     *
     * @param string $provider e.g., 'groq'
     * @param string $model e.g., 'llama3-8b-8192'
     * @param int $promptTokens
     * @param int $completionTokens
     * @return float Estimated cost in USD
     */
    public function estimate(string $provider, string $model, int $promptTokens, int $completionTokens): float;
}
