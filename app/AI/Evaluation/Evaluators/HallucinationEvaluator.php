<?php

namespace App\AI\Evaluation\Evaluators;

use App\AI\Evaluation\Contracts\EvaluatorInterface;
use App\AI\Evaluation\DTO\EvaluationResult;
use App\AI\Evaluation\DTO\GoldenDatasetItem;
use App\AI\Observability\DTO\AITrace;

/**
 * Checks for hallucinated information.
 * In a real-world scenario, this is often implemented via LLM-as-a-Judge.
 * For this initial version, we provide a structured placeholder strategy.
 */
class HallucinationEvaluator implements EvaluatorInterface
{
    public function getName(): string
    {
        return 'Hallucination';
    }

    public function evaluate(AITrace $trace, ?GoldenDatasetItem $expected = null): EvaluationResult
    {
        // FUTURE: Extract response content from the Trace and send it to a secondary evaluator model (e.g., GPT-4o)
        // along with the Context array. Ask the evaluator: "Does the response contain any facts not present in the context?"
        
        $metrics = $trace->getMetrics();

        // If the context size was 0, but the AI generated a long response (and no tools were used),
        // there is a high probability of hallucination if the prompt required grounded data.
        if ($metrics && $metrics->contextSizeBytes === 0 && empty($trace->getToolMetrics()) && $metrics->completionTokens > 100) {
            return new EvaluationResult(
                evaluatorName: $this->getName(),
                score: 0.5, // Suspicious
                reasoning: "High token output with zero context and zero tools. Potential hallucination risk."
            );
        }

        return new EvaluationResult(
            evaluatorName: $this->getName(),
            score: 1.0,
            reasoning: "No obvious hallucination indicators detected in trace metrics. (Requires LLM-as-a-Judge for deep semantic analysis)"
        );
    }
}
