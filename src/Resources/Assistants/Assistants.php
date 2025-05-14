<?php

namespace Sanjarani\OpenAI\Resources\Assistants;

use Sanjarani\OpenAI\Resources\AbstractResource;

class Assistants extends AbstractResource
{
    /**
     * Create an assistant.
     *
     * @param array $parameters
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/assistants/createAssistant
     */
    public function create(array $parameters, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("assistants", $parameters, $baseUrlOverride);
    }

    /**
     * Retrieves an assistant.
     *
     * @param string $assistantId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/assistants/getAssistant
     */
    public function retrieve(string $assistantId, ?string $baseUrlOverride = null): array
    {
        return $this->client->get("assistants/{$assistantId}", [], $baseUrlOverride);
    }

    /**
     * Modifies an assistant.
     *
     * @param string $assistantId
     * @param array $parameters
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/assistants/modifyAssistant
     */
    public function modify(string $assistantId, array $parameters, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("assistants/{$assistantId}", $parameters, $baseUrlOverride);
    }

    /**
     * Deletes an assistant.
     *
     * @param string $assistantId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/assistants/deleteAssistant
     */
    public function delete(string $assistantId, ?string $baseUrlOverride = null): array
    {
        return $this->client->delete("assistants/{$assistantId}", $baseUrlOverride);
    }

    /**
     * Returns a list of assistants.
     *
     * @param array $queryParameters Optional: limit, order, after, before
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/assistants/listAssistants
     */
    public function list(array $queryParameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->get("assistants", $queryParameters, $baseUrlOverride);
    }
}

