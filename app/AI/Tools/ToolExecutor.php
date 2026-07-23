<?php

namespace App\AI\Tools;

use InvalidArgumentException;

/**
 * Handles the execution and validation of tool calls from the AI.
 */
class ToolExecutor
{
    public function __construct(
        protected ToolRegistry $registry
    ) {
    }

    /**
     * Execute a tool by name with the given parameters.
     *
     * @param string $name
     * @param array $parameters
     * @return mixed
     */
    public function execute(string $name, array $parameters = []): mixed
    {
        $tool = $this->registry->resolve($name);

        $this->validateParameters($tool->inputSchema(), $parameters);

        return $tool->execute($parameters);
    }

    /**
     * Basic validation to ensure required parameters are present.
     *
     * @param array $schema
     * @param array $parameters
     * @return void
     * @throws InvalidArgumentException
     */
    protected function validateParameters(array $schema, array $parameters): void
    {
        if (isset($schema['required']) && is_array($schema['required'])) {
            foreach ($schema['required'] as $requiredField) {
                if (!array_key_exists($requiredField, $parameters)) {
                    throw new InvalidArgumentException("Missing required parameter: {$requiredField}");
                }
            }
        }
    }
}
