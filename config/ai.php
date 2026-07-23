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

];
