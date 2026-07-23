<?php

namespace App\AI\Security;

use App\AI\Exceptions\SecurityException;

/**
 * Ensures that the conversation remains within the restaurant management domain.
 */
class DomainGuard
{
    /**
     * Topics that are explicitly forbidden.
     */
    protected array $forbiddenKeywords = [
        'programming', 'laravel tutorial', 'vue tutorial', 'mathematics',
        'history', 'politics', 'religion', 'medical advice',
        'financial investment', 'crypto', 'bitcoin', 'general knowledge',
        'write an essay', 'translate', 'story generation', 'write a story'
    ];

    /**
     * Check the input for forbidden domain topics.
     *
     * @param string $input
     * @return void
     * @throws SecurityException
     */
    public function check(string $input): void
    {
        $lowercaseInput = strtolower($input);
        
        foreach ($this->forbiddenKeywords as $keyword) {
            if (str_contains($lowercaseInput, $keyword)) {
                throw new SecurityException("I'm sorry, I am a restaurant assistant and can only answer questions related to Dmario operations, sales, reservations, and management.");
            }
        }
    }
}
