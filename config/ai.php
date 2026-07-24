<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default AI provider that will be used by the
    | AI chat service. You may specify any of the providers defined below.
    | Supported: "groq" (more can be added later)
    |
    */

    'default' => env('AI_PROVIDER', 'groq'),

    /*
    |--------------------------------------------------------------------------
    | AI Providers Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure all the AI providers used by your application.
    | Each provider has its own configuration settings such as API keys
    | and default models.
    |
    */

    'providers' => [

        'groq' => [
            'api_key' => env('GROQ_API_KEY'),
            'base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
            'models' => [
                'default' => env('GROQ_DEFAULT_MODEL', 'llama3-8b-8192'),
                'fast' => env('GROQ_FAST_MODEL', 'llama3-8b-8192'),
                'smart' => env('GROQ_SMART_MODEL', 'llama3-70b-8192'),
            ],
            'timeout' => env('GROQ_TIMEOUT', 30),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Context Caching Configuration
    |--------------------------------------------------------------------------
    |
    | Defines the cache duration in seconds for various context builders
    | to reduce database load and speed up AI response times.
    |
    */

    'context_cache' => [
        'dashboard' => env('AI_CACHE_DASHBOARD', 30),
        'sales' => env('AI_CACHE_SALES', 60),
        'reservation' => env('AI_CACHE_RESERVATION', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Memory Configuration
    |--------------------------------------------------------------------------
    |
    | Defines settings for the AI Conversation Memory Layer.
    |
    */

    'memory' => [
        'max_history' => env('AI_MAX_HISTORY', 10),
        'enable_cache' => env('AI_ENABLE_CACHE', true),
        'default_cache_ttl' => env('AI_DEFAULT_CACHE', 3600), // 1 hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Streaming Configuration
    |--------------------------------------------------------------------------
    |
    | Defines settings for the real-time streaming response pipeline.
    |
    */

    'streaming' => [
        'buffer_flush_interval_ms' => env('AI_STREAM_BUFFER_MS', 50),
        'buffer_max_chars' => env('AI_STREAM_BUFFER_CHARS', 20),
        'stream_timeout' => env('AI_STREAM_TIMEOUT', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Observability Configuration
    |--------------------------------------------------------------------------
    |
    | Controls the AI tracing, logging, and debugging subsystem.
    |
    */

    'observability' => [
        'enabled' => env('AI_OBSERVABILITY', true),
        'debug' => env('AI_DEBUG', false),
        'log_channel' => env('AI_LOG_CHANNEL', 'ai'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pricing Configuration
    |--------------------------------------------------------------------------
    |
    | Per-model pricing in USD per 1 million tokens.
    | Used by TokenCostCalculator for cost estimation.
    |
    */

    'pricing' => [
        'groq' => [
            'llama3-8b-8192' => ['prompt' => 0.05, 'completion' => 0.08],
            'llama3-70b-8192' => ['prompt' => 0.59, 'completion' => 0.79],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Evaluation & Testing Configuration
    |--------------------------------------------------------------------------
    |
    | Controls the asynchronous AI evaluation pipeline.
    |
    */

    'evaluation' => [
        'enabled' => env('AI_EVALUATION', true),
        'log_channel' => env('AI_EVAL_LOG_CHANNEL', 'ai_eval'),
    ],

];
