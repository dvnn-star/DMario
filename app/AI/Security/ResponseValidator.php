<?php

namespace App\AI\Security;

use App\AI\DTO\AIResponse;
use App\AI\Exceptions\SecurityException;

/**
 * Validates the response from the AI provider before sending it back to the user.
 */
class ResponseValidator
{
    /**
     * Phrases that indicate the AI might be leaking internal structure or errors.
     */
    protected array $leakIndicators = [
        'APP_KEY',
        'DB_PASSWORD',
        'Stack trace:',
        'SQLSTATE',
        'Illuminate\\Database',
        '<?php',
        'ignore previous instructions', // if the AI echoes it back
    ];

    /**
     * Validate the AI response.
     *
     * @param AIResponse $response
     * @return AIResponse
     * @throws SecurityException
     */
    public function validate(AIResponse $response): AIResponse
    {
        $content = $response->content;

        foreach ($this->leakIndicators as $indicator) {
            if (str_contains($content, $indicator)) {
                throw new SecurityException("The AI response contained potentially sensitive internal information and was blocked.");
            }
        }

        // Additional validation could go here, e.g., checking for hallucinated 
        // JSON schemas if tools were used, etc.

        return $response;
    }
}
