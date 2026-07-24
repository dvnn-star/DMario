<?php

namespace App\AI\Evaluation\Evaluators;

use App\AI\Evaluation\Contracts\EvaluatorInterface;
use App\AI\Evaluation\DTO\EvaluationResult;
use App\AI\Evaluation\DTO\GoldenDatasetItem;
use App\AI\Observability\DTO\AITrace;

/**
 * Evaluates whether the AI actually utilized the context provided to it.
 */
class ContextAdherenceEvaluator implements EvaluatorInterface
{
    public function getName(): string
    {
        return 'ContextAdherence';
    }

    public function evaluate(AITrace $trace, ?GoldenDatasetItem $expected = null): EvaluationResult
    {
        $metrics = $trace->getMetrics();

        if (!$metrics) {
            return new EvaluationResult($this->getName(), 0.0, "No metrics available to evaluate context adherence.");
        }

        // If a large context was provided, but the AI output very few tokens, 
        // it may not have synthesized the context effectively (or it was a very concise answer).
        // This is a heuristic evaluation.
        
        if ($metrics->contextSizeBytes > 1000 && $metrics->completionTokens < 10) {
            return new EvaluationResult(
                evaluatorName: $this->getName(),
                score: 0.8,
                reasoning: "Large context provided but extremely brief response generated. Context may have been under-utilized."
            );
        }

        return new EvaluationResult(
            evaluatorName: $this->getName(),
            score: 1.0,
            reasoning: "Context size to completion token ratio appears normal."
        );
    }
}
