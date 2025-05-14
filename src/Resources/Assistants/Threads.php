<?php

namespace Sanjarani\OpenAI\Resources\Assistants;

use Sanjarani\OpenAI\Resources\AbstractResource;

class Threads extends AbstractResource
{
    /**
     * Create a thread.
     *
     * @param array $parameters Optional: messages, tool_resources, metadata
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/threads/createThread
     */
    public function create(array $parameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->post("threads", $parameters, $baseUrlOverride);
    }

    /**
     * Retrieves a thread.
     *
     * @param string $threadId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/threads/getThread
     */
    public function retrieve(string $threadId, ?string $baseUrlOverride = null): array
    {
        return $this->client->get("threads/{$threadId}", [], $baseUrlOverride);
    }

    /**
     * Modifies a thread.
     *
     * @param string $threadId
     * @param array $parameters Optional: tool_resources, metadata
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/threads/modifyThread
     */
    public function modify(string $threadId, array $parameters, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("threads/{$threadId}", $parameters, $baseUrlOverride);
    }

    /**
     * Deletes a thread.
     *
     * @param string $threadId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/threads/deleteThread
     */
    public function delete(string $threadId, ?string $baseUrlOverride = null): array
    {
        return $this->client->delete("threads/{$threadId}", $baseUrlOverride);
    }
}

