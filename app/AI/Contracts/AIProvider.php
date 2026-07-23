<?php

namespace App\AI\Contracts;

use App\AI\DTO\AIResponse;
use App\AI\DTO\ChatMessage;
use App\AI\Exceptions\AIException;

/**
 * Interface that all AI providers must implement.
 * Ensures a consistent API regardless of the underlying service (Groq, OpenAI, Anthropic, etc.).
 */
interface AIProvider
{
    /**
     * Send a sequence of messages to the AI provider.
     *
     * @param ChatMessage[] $messages An array of ChatMessage objects representing the conversation history.
     * @param array $options Optional provider-specific parameters (e.g., temperature, max_tokens).
     * @return AIResponse The standardized response from the provider.
     * @throws AIException If the request fails or is rate-limited.
     */
    public function sendMessage(array $messages, array $options = []): AIResponse;

    /**
     * Set the model to be used for subsequent requests.
     *
     * @param string $model
     * @return self
     */
    public function setModel(string $model): self;

    /**
     * Get the currently configured model.
     *
     * @return string
     */
    public function getModel(): string;
}
