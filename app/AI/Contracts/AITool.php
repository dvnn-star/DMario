<?php

namespace App\AI\Contracts;

/**
 * Interface that all AI tools must implement.
 */
interface AITool
{
    /**
     * The unique name of the tool (e.g., 'get_today_sales').
     */
    public function name(): string;

    /**
     * A clear description of what the tool does, used by the AI to decide when to call it.
     */
    public function description(): string;

    /**
     * The JSON schema defining the required inputs for this tool.
     */
    public function inputSchema(): array;

    /**
     * Execute the tool with the given parameters and return the result.
     *
     * @param array $parameters
     * @return mixed
     */
    public function execute(array $parameters): mixed;
}
