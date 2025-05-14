<?php

namespace Sanjarani\OpenAI\Resources;

use Sanjarani\OpenAI\Contracts\OpenAIClientContract;
use Sanjarani\OpenAI\Support\CacheManager;

class Embeddings extends AbstractResource
{
    protected CacheManager $cacheManager;

    public function __construct(OpenAIClientContract $client, array $config)
    {
        parent::__construct($client, $config);
        $this->cacheManager = new CacheManager($config);
    }

    /**
     * Creates an embedding vector representing the input text.
     *
     * @param array $parameters Requires: input (string or array of strings for batching), model.
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/embeddings/create
     */
    public function create(array $parameters, ?string $baseUrlOverride = null): array
    {
        if (!isset($parameters["model"])) {
            $parameters["model"] = $this->config["defaults"]["embeddings"] ?? "text-embedding-3-small";
        }

        return $this->cacheManager->get("embeddings.create", $parameters, function () use ($parameters, $baseUrlOverride) {
            return $this->client->post("embeddings", $parameters, $baseUrlOverride);
        });
    }

    /**
     * Creates embedding vectors for a batch of input texts.
     * This is a convenience method that utilizes the `input` parameter accepting an array.
     *
     * @param array $inputs An array of texts to embed.
     * @param string|null $model The model to use. Defaults to config or "text-embedding-3-small".
     * @param array $additionalParameters Other valid parameters for the embeddings endpoint (e.g., encoding_format, dimensions, user).
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/embeddings/create
     */
    public function createBatch(array $inputs, ?string $model = null, array $additionalParameters = [], ?string $baseUrlOverride = null): array
    {
        $model = $model ?? $this->config["defaults"]["embeddings"] ?? "text-embedding-3-small";
        
        $parameters = array_merge($additionalParameters, [
            "input" => $inputs,
            "model" => $model,
        ]);

        return $this->cacheManager->get("embeddings.create_batch", $parameters, function () use ($parameters, $baseUrlOverride) {
            return $this->client->post("embeddings", $parameters, $baseUrlOverride);
        });
    }
}

