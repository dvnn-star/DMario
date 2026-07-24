<?php

namespace App\AI\Evaluation;

use App\AI\Evaluation\Contracts\EvaluationStore;
use App\AI\Evaluation\DTO\EvaluationReport;
use App\AI\Evaluation\DTO\GoldenDatasetItem;
use App\AI\Observability\DTO\AITrace;
use Illuminate\Support\Facades\Log;

/**
 * Orchestrates the execution of evaluators on a given trace.
 */
class EvaluationPipeline
{
    public function __construct(
        protected EvaluatorRegistry $registry,
        protected EvaluationStore $store
    ) {
    }

    /**
     * Run all registered evaluators against the trace.
     *
     * @param AITrace $trace The request trace to evaluate
     * @param GoldenDatasetItem|null $expected The expected outcome, if available
     * @return EvaluationReport
     */
    public function evaluate(AITrace $trace, ?GoldenDatasetItem $expected = null): EvaluationReport
    {
        $report = EvaluationReport::create($trace->traceId);
        $evaluators = $this->registry->all();

        foreach ($evaluators as $evaluator) {
            try {
                $result = $evaluator->evaluate($trace, $expected);
                $report->addResult($result);
            } catch (\Throwable $e) {
                Log::error("Evaluator [{$evaluator->getName()}] failed: " . $e->getMessage(), [
                    'trace_id' => $trace->traceId,
                    'exception' => $e
                ]);
                
                // Add a failure result to indicate the evaluator crashed
                $report->addResult(new \App\AI\Evaluation\DTO\EvaluationResult(
                    evaluatorName: $evaluator->getName(),
                    score: 0.0,
                    reasoning: "Evaluator failed to execute: " . $e->getMessage()
                ));
            }
        }

        $this->store->store($report);

        return $report;
    }
}
