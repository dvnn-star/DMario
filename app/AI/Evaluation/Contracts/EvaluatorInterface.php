<?php

namespace App\AI\Evaluation\Contracts;

use App\AI\Evaluation\DTO\EvaluationResult;
use App\AI\Evaluation\DTO\GoldenDatasetItem;
use App\AI\Observability\DTO\AITrace;

/**
 * Contract for all evaluation strategies.
 */
interface EvaluatorInterface
{
    /**
     * Get the unique name of this evaluator (e.g., 'HallucinationEvaluator').
     */
    public function getName(): string;

    /**
     * Evaluate the given trace and return an EvaluationResult.
     *
     * @param AITrace $trace The complete trace of the request to evaluate
     * @param GoldenDatasetItem|null $expected The expected outcome, if running in a regression test
     * @return EvaluationResult
     */
    public function evaluate(AITrace $trace, ?GoldenDatasetItem $expected = null): EvaluationResult;
}
