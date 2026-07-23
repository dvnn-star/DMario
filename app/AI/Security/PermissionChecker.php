<?php

namespace App\AI\Security;

use App\AI\Exceptions\SecurityException;

/**
 * Checks if the current request or tool usage is permitted for the user.
 */
class PermissionChecker
{
    /**
     * Verify that the requested tool is in the whitelist.
     *
     * @param string $toolName
     * @param array $whitelist
     * @return void
     * @throws SecurityException
     */
    public function checkTool(string $toolName, array $whitelist): void
    {
        if (!in_array($toolName, $whitelist, true)) {
            throw new SecurityException("Unauthorized or unknown tool requested: {$toolName}");
        }
    }

    /**
     * Verify that the user has permission to access AI features.
     * Future integration point for Laravel Gates.
     *
     * @param mixed $user
     * @return void
     * @throws SecurityException
     */
    public function checkUserAccess($user): void
    {
        // For now, assume if this is called in the backend context, it's allowed.
        // But this is where $user->can('use-ai') would go.
        if (!$user) {
            throw new SecurityException("Authentication required to use AI features.");
        }
    }
}
