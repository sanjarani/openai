<?php

namespace Sanjarani\OpenAI\Resources;

use Sanjarani\OpenAI\Contracts\OpenAIClientContract;
use Sanjarani\OpenAI\Support\CacheManager;

class Chat extends AbstractResource
{
    protected CacheManager $cacheManager;

    public function __construct(OpenAIClientContract $client, array $config)
    {
        parent::__construct($client, $config);
        $this->cacheManager = new CacheManager($config);
    }

    /**
     * Creates a model response for the given chat conversation.
     *
     * @param array $parameters
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/chat/create
     */
    public function create(array $parameters, ?string $baseUrlOverride = null): array
    {
        if (!isset($parameters["model"])) {
            $parameters["model"] = $this->config["defaults"]["chat"] ?? "gpt-4o";
        }

        if (isset($parameters["stream"]) && $parameters["stream"] === true) {
            // This should be called via the stream method for clarity
            // Or throw an exception if stream is true and not handled by this specific method.
            // For now, we assume stream=false or not set for this non-streaming method.
            // Consider removing stream handling from here if a dedicated stream method exists and is preferred.
        }
        
        // Non-streaming requests can be cached
        return $this->cacheManager->get("chat.completions.create", $parameters, function () use ($parameters, $baseUrlOverride) {
            return $this->client->post("chat/completions", $parameters, $baseUrlOverride);
        });
    }

    /**
     * Creates a streaming model response for the given chat conversation.
     *
     * @param array $parameters
     * @param callable $callback
     * @param string|null $baseUrlOverride
     * @return void
     * @see https://platform.openai.com/docs/api-reference/chat/create (for stream parameter)
     */
    public function stream(array $parameters, callable $callback, ?string $baseUrlOverride = null): void
    {
        if (!isset($parameters["model"])) {
            $parameters["model"] = $this->config["defaults"]["chat"] ?? "gpt-4o";
        }
        $parameters["stream"] = true;

        $this->client->stream("chat/completions", $parameters, $callback, $baseUrlOverride);
    }
}

