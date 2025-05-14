# Sanjarani OpenAI Laravel Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sanjarani/openai.svg?style=flat-square)](https://packagist.org/packages/sanjarani/openai)
[![Total Downloads](https://img.shields.io/packagist/dt/sanjarani/openai.svg?style=flat-square)](https://packagist.org/packages/sanjarani/openai)

A comprehensive Laravel 12 package for interacting with the OpenAI API. This package provides an easy-to-use interface for all major OpenAI features, including Chat Completions, Embeddings, Image Generation, Audio Transcription/Translation, File Management, Fine-tuning, Moderations, Models, and the powerful Assistants API with Vector Store support. It also includes advanced features like configurable caching, batching, and flexible API endpoint configuration for optimized performance and cost-efficiency.

## Features

- Full coverage of the OpenAI REST API.
- Intuitive, fluent API design leveraging Laravel conventions.
- Support for all major OpenAI models.
- **Flexible API Endpoint Configuration**: Globally configurable `baseUrl` and per-request `baseUrl` override for maximum flexibility.
- **Chat Completions**: Including streaming responses.
- **Embeddings**: With batch creation support.
- **Image Generation**: Create, edit, and create variations of images.
- **Audio**: Transcribe audio to text and translate audio to English using Whisper models. Generate speech from text using TTS models.
- **Files**: Upload, list, retrieve, and delete files.
- **Fine-tuning**: Manage fine-tuning jobs and events.
- **Moderations**: Classify text against OpenAI's content policy.
- **Models**: List and retrieve available models.
- **Assistants API**: Full support for Assistants, Threads, Messages, and Runs, including tool calls and streaming. Compatible with Assistants API v2 via header configuration.
- **Vector Stores**: Manage vector stores and files within them, including batch operations.
- **Configurable Caching**: Cache API responses to reduce latency and costs, with support for various Laravel cache stores.
- **Retry Mechanism**: Automatic retries for transient server errors and rate limit issues.
- **Detailed Configuration**: Extensive configuration options for API keys, default models, HTTP client settings, caching, logging, and API base URL.
- **Laravel 12 Ready**: Built and tested for Laravel 12.
- **Composer Compatible**: Easy installation and autoloading.

## Requirements

- PHP 8.1+
- Laravel 11.x or 12.x
- An OpenAI API Key

## Installation

You can install the package via Composer:

```bash
composer require sanjarani/openai
```

The package will automatically register its service provider and facade.

Next, publish the configuration file:

```bash
php artisan vendor:publish --provider="Sanjarani\OpenAI\OpenAIServiceProvider" --tag="openai-config"
```

This will create a `config/openai.php` file in your application. You should add your OpenAI API Key and (optionally) Organization ID and Base URL to your `.env` file:

```env
OPENAI_API_KEY=your-api-key
OPENAI_ORGANIZATION_ID=your-organization-id # Optional
OPENAI_BASE_URL=https://api.openai.com/v1/ # Optional, defaults to this value

# Optional Caching Configuration
OPENAI_CACHE_ENABLED=true
OPENAI_CACHE_STORE=redis # Or your preferred Laravel cache store
OPENAI_CACHE_TTL=86400 # Cache TTL in seconds (e.g., 1 day)

# Optional: For Assistants API v2 or other beta features requiring specific headers
# Example: OPENAI_BETA_ASSISTANTS_VERSION=v2
```

## Configuration

The `config/openai.php` file allows you to configure various aspects of the package:

- `api_key`: Your OpenAI API key (preferably set via `.env`).
- `organization_id`: Your OpenAI Organization ID (optional, via `.env`).
- `base_url`: The base URL for the OpenAI API. Defaults to `https://api.openai.com/v1/`. Can be overridden per request.
- `openai_beta_headers`: An array to specify any `OpenAI-Beta` headers, e.g., `["OpenAI-Beta" => "assistants=v2"]`.
- `defaults`: Default models for chat, embeddings, audio, etc.
- `http`: HTTP client settings (timeout, retry attempts, retry delay).
- `caching`: Enable/disable caching, select cache store, set TTL, and configure endpoint-specific caching.
- `logging`: Enable/disable logging of API requests/responses.
- `assistants`: Default polling intervals and timeouts for Assistant runs.

### Configuring `OpenAI-Beta` Headers

To use features like Assistants API v2, you might need to send specific `OpenAI-Beta` headers. You can configure these in `config/openai.php`:

```php
// In config/openai.php
    // ...
    /*
    |--------------------------------------------------------------------------
    | OpenAI Beta Headers
    |--------------------------------------------------------------------------
    |
    | Specify any OpenAI-Beta headers required for accessing beta features.
    | For example, to use Assistants API v2: ["OpenAI-Beta" => "assistants=v2"]
    | The Client.php is set up to merge these headers into requests.
    |
    */
    "openai_beta_headers" => array_filter([
        // Example for Assistants v2, driven by an environment variable:
        env("OPENAI_BETA_ASSISTANTS_VERSION") ? "OpenAI-Beta" : null => 
            env("OPENAI_BETA_ASSISTANTS_VERSION") ? "assistants=" . env("OPENAI_BETA_ASSISTANTS_VERSION") : null,
        // Add other static or env-driven beta headers here:
        // "Another-Beta-Header" => "some-value",
    ]),
    // ...
```
This setup allows you to enable beta features like Assistants API v2 by setting `OPENAI_BETA_ASSISTANTS_VERSION=v2` in your `.env` file. The `Client.php` has been updated to automatically include these configured headers in all API requests.

## Usage

The package provides a Facade for easy access to its functionalities.

### Per-Request Base URL Override

All resource methods (e.g., `OpenAI::chat()->create(...)`, `OpenAI::embeddings()->create(...)`) now accept an optional final string parameter to override the `baseUrl` for that specific request. This is useful if you need to target a different API endpoint for a particular call (e.g., a beta endpoint or a custom proxy).

```php
use Sanjarani\OpenAI\Facades\OpenAI;

// Example: Using a custom base URL for a specific chat completion request
$customBaseUrl = "https://my-custom-openai-proxy.com/v1/";

$response = OpenAI::chat()->create([
    "model" => "gpt-4o",
    "messages" => [
        ["role" => "user", "content" => "Hello from a custom endpoint!"],
    ],
], $customBaseUrl); // Pass the custom base URL as the last argument

echo $response["choices"][0]["message"]["content"];
```
If no `baseUrlOverride` is provided, the `base_url` from your `config/openai.php` (or its default `https://api.openai.com/v1/`) will be used.

### Basic Example: Chat Completion

```php
use Sanjarani\OpenAI\Facades\OpenAI;

$response = OpenAI::chat()->create([
    "model" => "gpt-4o",
    "messages" => [
        ["role" => "user", "content" => "Hello! What is the capital of France?"],
    ],
]);

echo $response["choices"][0]["message"]["content"]; // Paris
```

### Streaming Chat Completion

```php
use Sanjarani\OpenAI\Facades\OpenAI;

OpenAI::chat()->stream([
    "model" => "gpt-4o",
    "messages" => [
        ["role" => "user", "content" => "Tell me a short story about a brave robot."],
    ],
], function ($chunk) {
    // $chunk is an array representing a part of the streamed response
    if (isset($chunk["choices"][0]["delta"]["content"])) {
        echo $chunk["choices"][0]["delta"]["content"];
    }
});
```

### Embeddings

```php
use Sanjarani\OpenAI\Facades\OpenAI;

// Single input
$response = OpenAI::embeddings()->create([
    "model" => "text-embedding-3-small",
    "input" => "The food was delicious and the waiter...",
]);

$embedding = $response["data"][0]["embedding"];

// Batch input with a custom base URL
$customUrl = "https://another-api.com/openai-compat/";
$response = OpenAI::embeddings()->createBatch([
    "The food was delicious and the waiter...",
    "The movie was amazing and the plot was thrilling!"
], "text-embedding-3-small", [], $customUrl);

foreach ($response["data"] as $embeddingData) {
    // $embeddingData["embedding"]
}
```

### Image Generation

```php
use Sanjarani\OpenAI\Facades\OpenAI;

$response = OpenAI::images()->create([
    "prompt" => "A futuristic cityscape with flying cars, digital art",
    "n" => 1,
    "size" => "1024x1024",
    "response_format" => "url", // or b64_json
]);

$imageUrl = $response["data"][0]["url"];
```

### Audio Transcription (Whisper)

```php
use Sanjarani\OpenAI\Facades\OpenAI;

$response = OpenAI::audio()->createTranscription([
    "file" => "/path/to/your/audio.mp3",
    "model" => "whisper-1",
]);

$transcribedText = $response["text"];
```

### Audio Speech (TTS)

```php
use Sanjarani\OpenAI\Facades\OpenAI;
use Illuminate\Support\Facades\Storage;

$audioContent = OpenAI::audio()->createSpeech([
    "model" => "tts-1",
    "input" => "Hello world! This is a test of the text-to-speech API.",
    "voice" => "alloy",
]);

Storage::disk("local")->put("speech.mp3", $audioContent);
```

### Assistants API Example

(Ensure `openai_beta_headers` in `config/openai.php` is set correctly if using Assistants API v2, e.g., by setting `OPENAI_BETA_ASSISTANTS_VERSION=v2` in your `.env` file.)

```php
use Sanjarani\OpenAI\Facades\OpenAI;

$assistant = OpenAI::assistants()->create([
    "name" => "Math Tutor",
    "instructions" => "You are a personal math tutor. Write and run code to answer math questions.",
    "tools" => [["type" => "code_interpreter"]],
    "model" => "gpt-4o"
]);
// ... (rest of the Assistants API flow as previously documented)
```

### Vector Stores (with Assistants API)

(Ensure `openai_beta_headers` in `config/openai.php` is set correctly if using Assistants API v2.)

```php
use Sanjarani\OpenAI\Facades\OpenAI;

// ... (Vector Store creation and Assistant setup as previously documented)
```

### Caching

If caching is enabled in `config/openai.php`, responses for configured endpoints will be cached automatically.

### Error Handling

The package throws specific exceptions for different types of API errors (as previously documented).

## Testing

(As previously documented)

## Contributing

(As previously documented)

## License

(As previously documented)

---

*This README provides a comprehensive overview. For specific API parameters and more detailed information, please refer to the [official OpenAI API documentation](https://platform.openai.com/docs/api-reference).*

