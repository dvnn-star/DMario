<?php

namespace App\AI\Security;

use App\AI\Exceptions\SecurityException;

/**
 * Guards against prompt injection, jailbreaks, and instruction overrides.
 */
class PromptGuard
{
    /**
     * Common attack vectors and jailbreak phrases.
     */
    protected array $blacklistedPatterns = [
        '/ignore (all )?(previous )?instructions/i',
        '/forget (all )?(previous )?instructions/i',
        '/reveal (your )?prompt/i',
        '/show (hidden )?prompt/i',
        '/pretend you are/i',
        '/act as (a )?developer/i',
        '/system prompt/i',
        '/bypass/i',
        '/override/i',
        '/jailbreak/i',
        '/DAN (Do Anything Now)/i',
    ];

    /**
     * Check the input for prompt injections.
     *
     * @param string $input
     * @return void
     * @throws SecurityException
     */
    public function check(string $input): void
    {
        foreach ($this->blacklistedPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                throw new SecurityException("Prompt injection attempt detected.");
            }
        }
    }
}
