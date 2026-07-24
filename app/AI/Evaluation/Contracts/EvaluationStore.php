<?php

namespace App\AI\Evaluation\Contracts;

use App\AI\Evaluation\DTO\EvaluationReport;

/**
 * Storage abstraction for evaluation reports.
 */
interface EvaluationStore
{
    /**
     * Persist an evaluation report.
     */
    public function store(EvaluationReport $report): void;

    /**
     * Retrieve an evaluation report by trace ID.
     */
    public function get(string $traceId): ?EvaluationReport;
}
