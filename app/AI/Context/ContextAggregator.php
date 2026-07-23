<?php

namespace App\AI\Context;

use App\AI\Context\Contracts\ContextBuilder;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

/**
 * Aggregates multiple context builders into a single, cohesive payload.
 */
class ContextAggregator
{
    /**
     * @var array<string, string> Map of context names to their builder classes.
     */
    protected array $builders = [];

    public function __construct(
        protected Container $container
    ) {
    }

    /**
     * Register a context builder class.
     *
     * @param string $builderClass
     * @return void
     */
    public function register(string $builderClass): void
    {
        if (!is_subclass_of($builderClass, ContextBuilder::class)) {
            throw new InvalidArgumentException("Class [{$builderClass}] must implement ContextBuilder interface.");
        }

        /** @var ContextBuilder $instance */
        $instance = $this->container->make($builderClass);
        $this->builders[$instance->getName()] = $builderClass;
    }

    /**
     * Request specific contexts and aggregate their outputs.
     *
     * @param array<string> $requestedContextNames Array of names like ['dashboard', 'sales']
     * @return array The combined JSON-serializable associative array
     * @throws InvalidArgumentException If a requested context is unknown
     */
    public function aggregate(array $requestedContextNames): array
    {
        $aggregatedContext = [];

        foreach ($requestedContextNames as $name) {
            if (!isset($this->builders[$name])) {
                throw new InvalidArgumentException("Unknown context requested: {$name}");
            }

            /** @var ContextBuilder $builder */
            $builder = $this->container->make($this->builders[$name]);
            
            // Build and attach to the main payload under its own namespace key
            $aggregatedContext[$name] = $builder->build();
        }

        return $aggregatedContext;
    }
}
