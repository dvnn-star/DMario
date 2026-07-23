<?php

namespace App\AI\Exceptions;

use Illuminate\Http\Client\Response;

/**
 * Exception thrown when an AI provider returns an error (e.g., HTTP error, rate limit).
 */
class AIProviderException extends AIException
{
    protected ?Response $response = null;

    /**
     * Create a new exception instance based on an HTTP response.
     */
    public static function fromResponse(Response $response, string $providerName): self
    {
        $status = $response->status();
        $message = $response->json('error.message') ?? $response->body() ?: "Unknown error from {$providerName} provider.";

        $exception = new static("{$providerName} Error [{$status}]: {$message}", $status);
        $exception->response = $response;

        return $exception;
    }

    /**
     * Get the underlying HTTP response if available.
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
