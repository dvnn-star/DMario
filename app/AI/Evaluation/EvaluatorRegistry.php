<?php

namespace App\AI\Evaluation;

use App\AI\Evaluation\Contracts\EvaluatorInterface;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

/**
 * Registry holding all available evaluation strategies.
 */
class EvaluatorRegistry
{
    /** @var array<string, string> */
    protected array $evaluators = [];

    public function __construct(
        protected Container $container
    ) {
    }

    /**
     * Register an evaluator class.
     */
    public function register(string $evaluatorClass): void
    {
        if (!is_subclass_of($evaluatorClass, EvaluatorInterface::class)) {
            throw new InvalidArgumentException("Class [{$evaluatorClass}] must implement EvaluatorInterface.");
        }

        /** @var EvaluatorInterface $instance */
        $instance = $this->container->make($evaluatorClass);
        $this->evaluators[$instance->getName()] = $evaluatorClass;
    }

    /**
     * Get an array of all registered evaluator instances.
     *
     * @return EvaluatorInterface[]
     */
    public function all(): array
    {
        $instances = [];
        foreach ($this->evaluators as $class) {
            $instances[] = $this->container->make($class);
        }
        return $instances;
    }
}
