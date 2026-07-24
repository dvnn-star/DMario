<?php

namespace App\AI\Evaluation\DTO;

/**
 * Represents the outcome of a single evaluation metric.
 */
readonly class EvaluationResult
{
    /**
     * @param string $evaluatorName The name of the evaluator (e.g., 'HallucinationEvaluator')
     * @param float $score A normalized score from 0.0 to 1.0
     * @param string|null $reasoning The explanation for why this score was given
     * @param array $metadata Any additional data relevant to this evaluation
     */
    public function __construct(
        public string $evaluatorName,
        public float $score,
        public ?string $reasoning = null,
        public array $metadata = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'evaluator_name' => $this->evaluatorName,
            'score' => $this->score,
            'reasoning' => $this->reasoning,
            'metadata' => $this->metadata,
        ];
    }
}
