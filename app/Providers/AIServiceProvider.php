<?php

namespace App\Providers;

use App\AI\Contracts\AIProvider;
use App\AI\Providers\GroqProvider;
use App\AI\Services\AIChatService;
use App\AI\Tools\ToolRegistry;
use App\AI\Tools\Implementations\GetTodaySalesTool;
use App\AI\Tools\Implementations\GetWeeklyRevenueTool;
use App\AI\Tools\Implementations\GetTopSellingMenusTool;
use App\AI\Tools\Implementations\GetReservationSummaryTool;
use App\AI\Tools\Implementations\GetRecentOrdersTool;
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

        // Auto-resolve AIChatService so it automatically injects all security guards
        $this->app->singleton(AIChatService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(ToolRegistry $registry, \App\AI\Context\ContextAggregator $contextAggregator): void
    {
        $this->publishes([
            __DIR__.'/../../config/ai.php' => config_path('ai.php'),
        ], 'ai-config');

        // Register tools in the registry
        $registry->register(GetTodaySalesTool::class);
        $registry->register(GetWeeklyRevenueTool::class);
        $registry->register(GetTopSellingMenusTool::class);
        $registry->register(GetReservationSummaryTool::class);
        $registry->register(GetRecentOrdersTool::class);

        // Register context builders in the aggregator
        $contextAggregator->register(\App\AI\Context\DashboardContext::class);
        $contextAggregator->register(\App\AI\Context\SalesContext::class);
        $contextAggregator->register(\App\AI\Context\OrderContext::class);
        $contextAggregator->register(\App\AI\Context\MenuContext::class);
        $contextAggregator->register(\App\AI\Context\ReservationContext::class);
        $contextAggregator->register(\App\AI\Context\TableContext::class);
    }
}
