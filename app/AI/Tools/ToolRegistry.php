<?php

namespace App\AI\Tools;

use App\AI\Contracts\AITool;
use App\AI\Exceptions\SecurityException;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

/**
 * Manages the registration and resolution of AI tools.
 */
class ToolRegistry
{
    /**
     * @var array<string, string> Map of tool names to their fully qualified class names.
     */
    protected array $tools = [];

    /**
     * @var array<string> List of tool names that are explicitly permitted to be executed.
     */
    protected array $whitelist = [];

    public function __construct(
        protected Container $container
    ) {
    }

    /**
     * Register a new tool class.
     *
     * @param string $toolClass
     * @return void
     * @throws InvalidArgumentException
     */
    public function register(string $toolClass): void
    {
        if (!is_subclass_of($toolClass, AITool::class)) {
            throw new InvalidArgumentException("Tool [{$toolClass}] must implement AITool interface.");
        }

        /** @var AITool $instance */
        $instance = $this->container->make($toolClass);
        $this->tools[$instance->name()] = $toolClass;
    }

    /**
     * Set the whitelist of allowed tool names.
     *
     * @param array $whitelist
     * @return void
     */
    public function setWhitelist(array $whitelist): void
    {
        $this->whitelist = $whitelist;
    }

    /**
     * Resolve a tool instance by name.
     *
     * @param string $name
     * @return AITool
     * @throws SecurityException
     * @throws InvalidArgumentException
     */
    public function resolve(string $name): AITool
    {
        if (!isset($this->tools[$name])) {
            throw new InvalidArgumentException("Unknown tool requested: {$name}");
        }

        if (!empty($this->whitelist) && !in_array($name, $this->whitelist, true)) {
            throw new SecurityException("Tool execution blocked: {$name} is not in the whitelist.");
        }

        return $this->container->make($this->tools[$name]);
    }

    /**
     * Get all registered tools, formatted for the AI provider's tool schema.
     *
     * @return array
     */
    public function getDefinitions(): array
    {
        $definitions = [];

        foreach ($this->tools as $name => $class) {
            // Only include whitelisted tools if a whitelist is active
            if (!empty($this->whitelist) && !in_array($name, $this->whitelist, true)) {
                continue;
            }

            /** @var AITool $instance */
            $instance = $this->container->make($class);

            $definitions[] = [
                'type' => 'function',
                'function' => [
                    'name' => $instance->name(),
                    'description' => $instance->description(),
                    'parameters' => $instance->inputSchema(),
                ]
            ];
        }

        return $definitions;
    }
}
