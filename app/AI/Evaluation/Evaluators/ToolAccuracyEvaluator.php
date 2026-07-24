<?php

namespace App\AI\Evaluation\Evaluators;

use App\AI\Evaluation\Contracts\EvaluatorInterface;
use App\AI\Evaluation\DTO\EvaluationResult;
use App\AI\Evaluation\DTO\GoldenDatasetItem;
use App\AI\Observability\DTO\AITrace;

/**
 * Evaluates whether the AI selected and executed the correct tools for the task.
 */
class ToolAccuracyEvaluator implements EvaluatorInterface
{
    public function getName(): string
    {
        return 'ToolAccuracy';
    }

    public function evaluate(AITrace $trace, ?GoldenDatasetItem $expected = null): EvaluationResult
    {
        $toolsExecuted = $trace->getToolMetrics();

        // 1. If running outside a regression test, we can only evaluate based on success/failure metrics
        if (!$expected) {
            if (empty($toolsExecuted)) {
                return new EvaluationResult($this->getName(), 1.0, "No tools executed, nothing to evaluate.");
            }

            $failures = collect($toolsExecuted)->filter(fn($t) => !$t->success)->count();
            $score = $failures > 0 ? max(0.0, 1.0 - ($failures * 0.5)) : 1.0;

            return new EvaluationResult(
                evaluatorName: $this->getName(),
                score: $score,
                reasoning: $failures > 0 ? "{$failures} tool(s) failed during execution." : "All tools executed successfully."
            );
        }

        // 2. If we have a Golden Dataset item, check if the exact expected tools were called
        $expectedTools = $expected->expectedTools;
        $actualTools = array_map(fn($t) => $t->toolName, $toolsExecuted);

        // Sort to ignore order
        sort($expectedTools);
        sort($actualTools);

        if ($expectedTools === $actualTools) {
            return new EvaluationResult($this->getName(), 1.0, "AI called the exact expected tools.");
        }

        return new EvaluationResult(
            evaluatorName: $this->getName(),
            score: 0.0,
            reasoning: "Tool mismatch. Expected: [" . implode(', ', $expectedTools) . "], Actual: [" . implode(', ', $actualTools) . "]",
            metadata: ['expected' => $expectedTools, 'actual' => $actualTools]
        );
    }
}
