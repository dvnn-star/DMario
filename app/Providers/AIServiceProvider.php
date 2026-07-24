<?php

namespace App\Providers;

use App\AI\Contracts\AIProvider;
use App\AI\Providers\GroqProvider;
use App\AI\Services\AIChatService;
use App\AI\Tools\ToolRegistry;
use App\AI\Tools\Implementations\AnalyzeRevenueTool;
use App\AI\Tools\Implementations\AnalyzeOrdersTool;
use App\AI\Tools\Implementations\AnalyzeMenuPerformanceTool;
use App\AI\Tools\Implementations\AnalyzePaymentsTool;
use App\AI\Tools\Implementations\UpdateOrderStatusTool;
use App\AI\Tools\Implementations\SearchKnowledgeBaseTool;
use App\AI\Tools\Implementations\GetCategoriesTool;
use App\AI\Tools\Implementations\GetTableStatusTool;
use App\AI\Tools\Implementations\GetPendingReservationsTool;
use App\AI\Tools\Implementations\GetAvailableMenusTool;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use App\Repositories\ReservationRepository;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\MenuRepository;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/ai.php', 'ai'
        );

        // Bind Repositories
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(\App\Repositories\Contracts\SalesRepositoryInterface::class, \App\Repositories\SalesRepository::class);
        $this->app->bind(\App\Repositories\Contracts\TableRepositoryInterface::class, \App\Repositories\TableRepository::class);
        $this->app->bind(\App\Repositories\Contracts\DashboardRepositoryInterface::class, \App\Repositories\DashboardRepository::class);
        $this->app->bind(\App\Repositories\Contracts\CategoryRepositoryInterface::class, \App\Repositories\CategoryRepository::class);
        $this->app->singleton(\App\Repositories\KnowledgeBaseRepository::class);

        // Bind Memory Layer
        $this->app->bind(\App\AI\Memory\Contracts\MemoryStore::class, \App\AI\Memory\Stores\SessionMemoryStore::class);
        $this->app->bind(\App\AI\Memory\Contracts\MemorySummarizer::class, \App\AI\Memory\Strategies\SummarizationStrategy::class);
        $this->app->singleton(\App\AI\Memory\Contracts\MemoryManager::class, \App\AI\Memory\Services\ConversationMemoryManager::class);

        // Bind AI Provider
        $this->app->singleton(AIProvider::class, function ($app) {
            $defaultProvider = $app['config']->get('ai.default');

            if ($defaultProvider === 'groq') {
                return new GroqProvider($app['config']->get('ai.providers.groq', []));
            }

            throw new InvalidArgumentException("Unsupported AI provider: {$defaultProvider}");
        });

        // Bind Tool Registry as singleton
        $this->app->singleton(ToolRegistry::class);

        // Bind Context Aggregator as singleton
        $this->app->singleton(\App\AI\Context\ContextAggregator::class);

        // Bind Prompt Resolver as singleton
        $this->app->singleton(\App\AI\Prompts\Resolver\PromptResolver::class);

        // Bind Observability Layer
        $this->app->bind(\App\AI\Observability\Contracts\MetricsStore::class, \App\AI\Observability\Stores\LogMetricsStore::class);
        $this->app->bind(\App\AI\Observability\Contracts\CostCalculator::class, \App\AI\Observability\CostCalculator\TokenCostCalculator::class);
        $this->app->singleton(\App\AI\Observability\AITracer::class);

        // Bind Evaluation Layer
        $this->app->singleton(\App\AI\Evaluation\EvaluatorRegistry::class);
        $this->app->bind(\App\AI\Evaluation\Contracts\EvaluationStore::class, \App\AI\Evaluation\Stores\LogEvaluationStore::class);
        $this->app->singleton(\App\AI\Evaluation\EvaluationPipeline::class);

        // Bind Orchestrator Layer
        $this->app->singleton(\App\AI\Orchestrator\ContextFormatter::class);
        $this->app->singleton(\App\AI\Orchestrator\AIOrchestrator::class);

        // Auto-resolve AIChatService so it automatically injects all security guards
        $this->app->singleton(AIChatService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(
        ToolRegistry $registry, 
        \App\AI\Context\ContextAggregator $contextAggregator,
        \App\AI\Prompts\Resolver\PromptResolver $promptResolver,
        \App\AI\Evaluation\EvaluatorRegistry $evaluatorRegistry
    ): void
    {
        $this->publishes([
            __DIR__.'/../../config/ai.php' => config_path('ai.php'),
        ], 'ai-config');

        // Register tools in the registry
        $registry->register(AnalyzeRevenueTool::class);
        $registry->register(AnalyzeOrdersTool::class);
        $registry->register(AnalyzeMenuPerformanceTool::class);
        $registry->register(AnalyzePaymentsTool::class);
        $registry->register(\App\AI\Tools\Implementations\GetReservationTool::class);
        $registry->register(UpdateOrderStatusTool::class);
        $registry->register(SearchKnowledgeBaseTool::class);
        $registry->register(GetCategoriesTool::class);
        $registry->register(GetTableStatusTool::class);
        $registry->register(GetPendingReservationsTool::class);
        $registry->register(GetAvailableMenusTool::class);

        // Register context builders in the aggregator
        $contextAggregator->register(\App\AI\Context\DashboardContext::class);
        $contextAggregator->register(\App\AI\Context\SalesContext::class);
        $contextAggregator->register(\App\AI\Context\OrderContext::class);
        $contextAggregator->register(\App\AI\Context\MenuContext::class);
        $contextAggregator->register(\App\AI\Context\ReservationContext::class);
        $contextAggregator->register(\App\AI\Context\TableContext::class);

        // Register prompt builders in the resolver
        $promptResolver->register(\App\AI\Prompts\Builders\DashboardPrompt::class);
        $promptResolver->register(\App\AI\Prompts\Builders\SalesAnalysisPrompt::class);
        $promptResolver->register(\App\AI\Prompts\Builders\OrderInsightPrompt::class);
        $promptResolver->register(\App\AI\Prompts\Builders\MenuAnalysisPrompt::class);
        $promptResolver->register(\App\AI\Prompts\Builders\ReservationPrompt::class);
        
        // Register the fallback
        $promptResolver->registerDefault(\App\AI\Prompts\Builders\GeneralPrompt::class);

        // Register Evaluators
        $evaluatorRegistry->register(\App\AI\Evaluation\Evaluators\DomainComplianceEvaluator::class);
        $evaluatorRegistry->register(\App\AI\Evaluation\Evaluators\ToolAccuracyEvaluator::class);
        $evaluatorRegistry->register(\App\AI\Evaluation\Evaluators\HallucinationEvaluator::class);
        $evaluatorRegistry->register(\App\AI\Evaluation\Evaluators\ContextAdherenceEvaluator::class);

        // Register Event Listeners
        \Illuminate\Support\Facades\Event::listen(
            \App\AI\Observability\Events\AIRequestCompleted::class,
            \App\AI\Evaluation\Listeners\RunEvaluationPipeline::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\TriggerProactiveAgent::class,
            function (\App\Events\TriggerProactiveAgent $event) {
                \App\Jobs\RunProactiveAgentJob::dispatch($event);
            }
        );
    }
}
