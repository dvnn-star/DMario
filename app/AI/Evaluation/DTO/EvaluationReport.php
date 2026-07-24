<?php

namespace App\AI\Evaluation\DTO;

/**
 * Aggregates multiple EvaluationResults for a single trace.
 */
class EvaluationReport
{
    /** @var EvaluationResult[] */
    protected array $results = [];

    public function __construct(
        public readonly string $traceId,
        public readonly float $evaluatedAt
    ) {
    }

    public static function create(string $traceId): self
    {
        return new self($traceId, microtime(true));
    }

    public function addResult(EvaluationResult $result): void
    {
        $this->results[] = $result;
    }

    /**
     * @return EvaluationResult[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * Calculate an overall average score across all evaluators.
     */
    public function getOverallScore(): float
    {
        if (empty($this->results)) {
            return 0.0;
        }

        $totalScore = array_sum(array_map(fn(EvaluationResult $r) => $r->score, $this->results));
        
        return round($totalScore / count($this->results), 2);
    }

    public function toArray(): array
    {
        return [
            'trace_id' => $this->traceId,
            'evaluated_at' => date('c', (int) $this->evaluatedAt),
            'overall_score' => $this->getOverallScore(),
            'results' => array_map(fn(EvaluationResult $r) => $r->toArray(), $this->results),
        ];
    }
}
