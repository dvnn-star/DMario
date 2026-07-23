<?php

namespace App\AI\Security;

/**
 * Sanitizes user input before any processing or AI provider interaction.
 */
class InputSanitizer
{
    /**
     * Sanitize the given input string.
     *
     * @param string $input
     * @return string
     */
    public function sanitize(string $input): string
    {
        // Remove null bytes and basic control characters
        $sanitized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        
        // Trim excess whitespace
        $sanitized = trim($sanitized);
        
        // Limit maximum raw length to prevent buffer/payload exhaustion before token counting
        // 10,000 characters is a reasonable upper limit for a single chat message
        if (mb_strlen($sanitized) > 10000) {
            $sanitized = mb_substr($sanitized, 0, 10000);
        }

        return $sanitized;
    }
}
