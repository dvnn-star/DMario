<?php

namespace App\AI\Contracts;

use App\AI\DTO\AIResponse;
use App\AI\DTO\ChatMessage;
use App\AI\Exceptions\AIException;
use App\AI\Streaming\StreamResponse;

/**
 * Interface that all AI providers must implement.
 * Ensures a consistent API regardless of the underlying service (Groq, OpenAI, Anthropic, etc.).
 */
interface AIProvider
{
    /**
     * Send a sequence of messages to the AI provider (synchronous).
     *
     * @param ChatMessage[] $messages An array of ChatMessage objects representing the conversation history.
     * @param array $options Optional provider-specific parameters (e.g., temperature, max_tokens).
     * @return AIResponse The standardized response from the provider.
     * @throws AIException If the request fails or is rate-limited.
     */
    public function sendMessage(array $messages, array $options = []): AIResponse;

    /**
     * Stream a sequence of messages to the AI provider (chunked).
     *
     * @param ChatMessage[] $messages An array of ChatMessage objects representing the conversation history.
     * @param array $options Optional provider-specific parameters.
     * @return StreamResponse An iterable cursor yielding StreamChunk DTOs.
     * @throws AIException If the request fails before the stream starts.
     */
    public function stream(array $messages, array $options = []): StreamResponse;

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
