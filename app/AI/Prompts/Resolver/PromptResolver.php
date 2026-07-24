<?php

namespace App\AI\Prompts\Resolver;

use App\AI\Prompts\Contracts\Prompt;
use App\AI\Prompts\DTO\PromptRequest;
use App\AI\Prompts\DTO\PromptResponse;
use Illuminate\Contracts\Container\Container;

class PromptResolver
{
    /**
     * @var string[] Array of Prompt Builder class names
     */
    protected array $builders = [];

    /**
     * @var string Default fallback builder class name
     */
    protected string $defaultBuilder;

    public function __construct(
        protected Container $container
    ) {
    }

    /**
     * Register a Prompt Builder class.
     */
    public function register(string $builderClass): void
    {
        $this->builders[] = $builderClass;
    }

    /**
     * Register the fallback Prompt Builder.
     */
    public function registerDefault(string $builderClass): void
    {
        $this->defaultBuilder = $builderClass;
    }

    /**
     * Resolve the request to the appropriate Prompt Builder and generate the PromptResponse.
     *
     * @param PromptRequest $request
     * @return PromptResponse
     */
    public function resolve(PromptRequest $request): PromptResponse
    {
        foreach ($this->builders as $builderClass) {
            /** @var Prompt $builder */
            $builder = $this->container->make($builderClass);

            if ($builder->supports($request)) {
                return $builder->build($request);
            }
        }

        // Fallback
        /** @var Prompt $default */
        $default = $this->container->make($this->defaultBuilder);
        return $default->build($request);
    }
}
