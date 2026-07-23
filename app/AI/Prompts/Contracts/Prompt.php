<?php

namespace App\AI\Prompts\Contracts;

use App\AI\Prompts\DTO\PromptRequest;
use App\AI\Prompts\DTO\PromptResponse;

interface Prompt
{
    /**
     * Get the name/identifier of this prompt builder.
     */
    public function getName(): string;

    /**
     * Check if this prompt builder supports the given user request intent.
     *
     * @param PromptRequest $request
     * @return bool
     */
    public function supports(PromptRequest $request): bool;

    /**
     * Build the PromptResponse from the given request and context.
     *
     * @param PromptRequest $request
     * @return PromptResponse
     */
    public function build(PromptRequest $request): PromptResponse;
}
