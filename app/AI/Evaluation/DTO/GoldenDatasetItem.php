<?php

namespace App\AI\Evaluation\DTO;

/**
 * Defines a test case for regression and benchmark testing.
 */
readonly class GoldenDatasetItem
{
    public function __construct(
        public string $id,
        public string $category,
        public string $input,
        public ?string $expectedOutput = null,
        public array $expectedTools = [],
        public array $evaluationRules = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category,
            'input' => $this->input,
            'expected_output' => $this->expectedOutput,
            'expected_tools' => $this->expectedTools,
            'evaluation_rules' => $this->evaluationRules,
        ];
    }
}
