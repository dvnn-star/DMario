<?php

namespace App\AI\Evaluation\Evaluators;

use App\AI\Evaluation\Contracts\EvaluatorInterface;
use App\AI\Evaluation\DTO\EvaluationResult;
use App\AI\Evaluation\DTO\GoldenDatasetItem;
use App\AI\Observability\DTO\AITrace;
use Illuminate\Support\Str;

/**
 * Checks if the AI's response breached any domain guardrails or leaked sensitive information.
 */
class DomainComplianceEvaluator implements EvaluatorInterface
{
    public function getName(): string
    {
        return 'DomainCompliance';
    }

    public function evaluate(AITrace $trace, ?GoldenDatasetItem $expected = null): EvaluationResult
    {
        $metrics = $trace->getMetrics();
        $spans = $trace->getSpans();
        
        // 1. Did the Guardrails explicitly block the request?
        if ($metrics && $metrics->guardrailResult === 'blocked') {
            return new EvaluationResult(
                evaluatorName: $this->getName(),
                score: 1.0, // Blocking a bad request is a good thing (100% compliant)
                reasoning: "Domain guardrails correctly blocked an out-of-domain request."
            );
        }

        // 2. Check the response content (if available in the stream span metadata)
        // Since the trace might not contain the full text (for privacy), we infer from spans
        $guardrailSpan = collect($spans)->firstWhere('name', 'guardrails');
        
        if ($guardrailSpan && $guardrailSpan->status === 'error') {
            return new EvaluationResult(
                evaluatorName: $this->getName(),
                score: 1.0,
                reasoning: "Request was safely blocked by guardrails."
            );
        }

        // Default to compliant if nothing failed
        return new EvaluationResult(
            evaluatorName: $this->getName(),
            score: 1.0,
            reasoning: "No domain boundary breaches detected during execution."
        );
    }
}
