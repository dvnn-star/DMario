<?php

namespace App\AI\Providers;

use App\AI\Contracts\AIProvider;
use App\AI\DTO\AIResponse;
use App\AI\Exceptions\AIProviderException;
use App\AI\Streaming\Parsers\SSEChunkParser;
use App\AI\Streaming\StreamResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class GroqProvider implements AIProvider
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $model;
    protected int $timeout;

    public function __construct(array $config)
    {
        $this->apiKey = $config['api_key'] ?? '';
        $this->baseUrl = $config['base_url'] ?? 'https://api.groq.com/openai/v1';
        $this->model = $config['models']['default'] ?? 'llama3-8b-8192';
        $this->timeout = $config['timeout'] ?? 30;
    }

    public function sendMessage(array $messages, array $options = []): AIResponse
    {
        $formattedMessages = array_map(fn ($msg) => $msg->toArray(), $messages);

        $payload = array_merge([
            'model' => $this->model,
            'messages' => $formattedMessages,
        ], $options);

        Log::debug('Sending request to Groq', ['model' => $this->model, 'messages_count' => count($messages)]);

        $response = Http::withToken($this->apiKey)
            ->timeout($this->timeout)
            ->post("{$this->baseUrl}/chat/completions", $payload);

        if ($response->failed()) {
            Log::error('Groq API Error', ['status' => $response->status(), 'body' => $response->body()]);
            throw AIProviderException::fromResponse($response, 'Groq');
        }

        return $this->parseResponse($response);
    }

    public function stream(array $messages, array $options = []): StreamResponse
    {
        $formattedMessages = array_map(fn ($msg) => $msg->toArray(), $messages);

        $payload = array_merge([
            'model' => $this->model,
            'messages' => $formattedMessages,
            'stream' => true,
        ], $options);

        Log::debug('Starting Groq stream', ['model' => $this->model, 'messages_count' => count($messages)]);

        // Use Guzzle directly for streaming support
        $client = new \GuzzleHttp\Client();

        $response = $client->post("{$this->baseUrl}/chat/completions", [
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
                'Accept' => 'text/event-stream',
            ],
            'json' => $payload,
            'stream' => true,
            'timeout' => $this->timeout,
        ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            Log::error('Groq Stream Error', ['status' => $statusCode]);
            throw new AIProviderException("Groq streaming failed with status {$statusCode}", $statusCode);
        }

        $httpStream = $response->getBody();

        return new StreamResponse($httpStream, new SSEChunkParser(), 'groq');
    }

    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Maps the Groq API JSON response to our standardized AIResponse DTO.
     */
    protected function parseResponse(Response $response): AIResponse
    {
        $data = $response->json();

        return new AIResponse(
            content: $data['choices'][0]['message']['content'] ?? null,
            model: $data['model'] ?? $this->model,
            promptTokens: $data['usage']['prompt_tokens'] ?? null,
            completionTokens: $data['usage']['completion_tokens'] ?? null,
            totalTokens: $data['usage']['total_tokens'] ?? null,
            rawResponse: $data,
            toolCalls: $data['choices'][0]['message']['tool_calls'] ?? []
        );
    }
}
