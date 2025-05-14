<?php

namespace Sanjarani\OpenAI\Resources;

use Sanjarani\OpenAI\Contracts\OpenAIClientContract;

class Models extends AbstractResource
{
    /**
     * Lists the currently available models, and provides basic information about each one.
     *
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/models/list
     */
    public function list(?string $baseUrlOverride = null): array
    {
        return $this->client->get("models", [], $baseUrlOverride);
    }

    /**
     * Retrieves a model instance, providing basic information about the model such as the owner and permissioning.
     *
     * @param string $modelId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/models/retrieve
     */
    public function retrieve(string $modelId, ?string $baseUrlOverride = null): array
    {
        return $this->client->get("models/{$modelId}", [], $baseUrlOverride);
    }

    /**
     * Delete a fine-tuned model. You must have the Owner role in your organization to delete a model.
     *
     * @param string $modelId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/models/delete
     */
    public function delete(string $modelId, ?string $baseUrlOverride = null): array
    {
        return $this->client->delete("models/{$modelId}", $baseUrlOverride);
    }
}

