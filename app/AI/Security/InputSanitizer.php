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
        // 1. Unicode Normalization (prevents zero-width space bypasses)
        if (class_exists('Normalizer')) {
            $input = \Normalizer::normalize($input, \Normalizer::FORM_C) ?: $input;
        }

        // 2. Strip HTML Tags to prevent XSS (Code/HTML/Markdown Injection)
        $input = strip_tags($input);

        // 3. Remove null bytes, basic control characters, and zero-width spaces (\x{200B})
        $sanitized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F\x{200B}]/u', '', $input);
        
        // 4. Trim excess whitespace
        $sanitized = trim($sanitized);
        
        // 5. Limit maximum raw length to prevent buffer/payload exhaustion before token counting
        // 10,000 characters is a reasonable upper limit for a single chat message
        if (mb_strlen($sanitized) > 10000) {
            $sanitized = mb_substr($sanitized, 0, 10000);
        }

        return $sanitized;
    }
}
