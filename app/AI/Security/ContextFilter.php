<?php

namespace App\AI\Security;

/**
 * Filters the business data context to ensure no secrets or unnecessary heavy objects are sent to the AI.
 */
class ContextFilter
{
    /**
     * Keys that should never be sent to the AI.
     */
    protected array $blacklistedKeys = [
        'password',
        'remember_token',
        'api_token',
        'stripe_secret',
        'secret',
        'key',
        'hash',
        'database',
        'host',
        'port'
    ];

    /**
     * Filter an array of context data.
     *
     * @param array $context
     * @return array
     */
    public function filter(array $context): array
    {
        $filtered = [];

        foreach ($context as $key => $value) {
            // Check if key is blacklisted
            if ($this->isBlacklistedKey((string)$key)) {
                continue;
            }

            // Recursively filter arrays
            if (is_array($value)) {
                $filtered[$key] = $this->filter($value);
            } 
            // If it's an object, we might want to convert to array or block it entirely unless it's a specific DTO
            elseif (is_object($value)) {
                if (method_exists($value, 'toArray')) {
                    $filtered[$key] = $this->filter($value->toArray());
                } else {
                    // Safe fallback: ignore complex objects that don't serialize easily
                    // to prevent leaking full Model traces
                    $filtered[$key] = '[Complex Object Redacted]';
                }
            } else {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    /**
     * Determine if a key is blacklisted.
     */
    protected function isBlacklistedKey(string $key): bool
    {
        $lowercaseKey = strtolower($key);
        foreach ($this->blacklistedKeys as $blacklistedKey) {
            if (str_contains($lowercaseKey, $blacklistedKey)) {
                return true;
            }
        }
        return false;
    }
}
