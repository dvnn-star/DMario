<?php

namespace App\AI\Context\Contracts;

/**
 * Interface that all AI Context Builders must implement.
 */
interface ContextBuilder
{
    /**
     * Get the unique identifier/name of this context (e.g., 'dashboard', 'sales').
     */
    public function getName(): string;

    /**
     * Build the context payload for the AI.
     * Must return an associative array of summarized business data.
     */
    public function build(): array;
}
