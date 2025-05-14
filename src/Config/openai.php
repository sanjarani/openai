<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization ID
    |--------------------------------------------------------------------------
    |
    | Your OpenAI API key and (optionally) organization ID.
    | You can find your API Key on your OpenAI dashboard.
    |
    */
    'api_key' => env('OPENAI_API_KEY'),
    'organization_id' => env('OPENAI_ORGANIZATION_ID'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the OpenAI API. Defaults to OpenAI's production API.
    | You might want to change this if you are using a proxy or a different API version endpoint.
    |
    */
    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1/'),

    /*
    |--------------------------------------------------------------------------
    | Default Models
    |--------------------------------------------------------------------------
    |
    | Specify default models for various OpenAI functionalities.
    | This helps in avoiding repetitive model specification in your code.
    |
    */
    'defaults' => [
        'chat' => 'gpt-4o',
        'embeddings' => 'text-embedding-3-small',
        'audio_transcription' => 'whisper-1',
        'audio_translation' => 'whisper-1',
        // Add other defaults as needed (e.g., for fine-tuning, image generation)
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the underlying HTTP client (Guzzle) settings.
    |
    */
    'http' => [
        'timeout' => 30, // seconds
        'retry_max_attempts' => 3,
        'retry_delay_ms' => 1000, // milliseconds
        // You can add any other Guzzle-specific options here
        // 'proxy' => 'http://localhost:8080',
        // 'verify' => false, // for SSL verification
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching for OpenAI API responses to optimize costs and performance.
    |
    */
    'caching' => [
        'enabled' => env('OPENAI_CACHE_ENABLED', false),
        'store' => env('OPENAI_CACHE_STORE', 'file'), // Laravel cache store (e.g., 'redis', 'memcached')
        'ttl' => env('OPENAI_CACHE_TTL', 60 * 60 * 24), // Default TTL in seconds (1 day)
        'prefix' => 'openai_api_cache',
        'endpoints' => [
            // Example: Cache chat completions for 1 hour
            // 'chat.completions.create' => 60 * 60, 
            // Example: Cache embeddings indefinitely (or very long TTL)
            // 'embeddings.create' => null, // null or a very high number for 'forever'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure logging for API requests and responses for debugging.
    |
    */
    'logging' => [
        'enabled' => env('OPENAI_LOGGING_ENABLED', false),
        'channel' => env('OPENAI_LOG_CHANNEL', 'stack'), // Laravel log channel
        'level' => env('OPENAI_LOG_LEVEL', 'debug'), // Log level (e.g., 'info', 'debug')
    ],

    /*
    |--------------------------------------------------------------------------
    | Assistants API Settings
    |--------------------------------------------------------------------------
    |
    | Configuration specific to the Assistants API.
    |
    */
    'assistants' => [
        'default_polling_interval_ms' => 2000, // Default polling interval for run status in milliseconds
        'default_run_timeout_ms' => 300000, // Default timeout for a run to complete in milliseconds (5 minutes)
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific beta features or behaviors of the package.
    |
    */
    'feature_flags' => [
        // 'some_beta_feature_enabled' => env('OPENAI_BETA_FEATURE_ENABLED', false),
    ],
];

