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
        // Classic Prompt Injections
        '/ignore (all )?(previous )?instructions/i',
        '/forget (all )?(previous )?instructions/i',
        '/bypass/i',
        '/override/i',
        
        // System Prompt Leakage
        '/reveal (your )?(system )?prompt/i',
        '/show (hidden )?prompt/i',
        '/what (was|were) your (initial|first) (instructions|prompt)/i',
        '/system prompt/i',
        '/repeat (the )?words above/i',
        
        // Role Injection & Jailbreaks
        '/pretend you are/i',
        '/act as (a )?(developer|admin|system|attacker)/i',
        '/you are now/i',
        '/from now on/i',
        '/jailbreak/i',
        '/DAN (Do Anything Now)/i',
        '/Developer Mode (enabled)?/i',
        
        // Data Exfiltration Attempts
        '/send (data|this|output) to/i',
        '/make an http request/i',
        '/curl /i',
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
