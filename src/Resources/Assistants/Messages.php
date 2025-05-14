<?php

namespace Sanjarani\OpenAI\Resources\Assistants;

use Sanjarani\OpenAI\Resources\AbstractResource;

class Messages extends AbstractResource
{
    /**
     * Create a message.
     *
     * @param string $threadId
     * @param array $parameters Requires: role, content. Optional: attachments, metadata
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/messages/createMessage
     */
    public function create(string $threadId, array $parameters, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("threads/{$threadId}/messages", $parameters, $baseUrlOverride);
    }

    /**
     * Retrieve a message.
     *
     * @param string $threadId
     * @param string $messageId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/messages/getMessage
     */
    public function retrieve(string $threadId, string $messageId, ?string $baseUrlOverride = null): array
    {
        return $this->client->get("threads/{$threadId}/messages/{$messageId}", [], $baseUrlOverride);
    }

    /**
     * Modifies a message.
     *
     * @param string $threadId
     * @param string $messageId
     * @param array $parameters Optional: metadata
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/messages/modifyMessage
     */
    public function modify(string $threadId, string $messageId, array $parameters, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("threads/{$threadId}/messages/{$messageId}", $parameters, $baseUrlOverride);
    }

    /**
     * Returns a list of messages for a given thread.
     *
     * @param string $threadId
     * @param array $queryParameters Optional: limit, order, after, before, run_id
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/messages/listMessages
     */
    public function list(string $threadId, array $queryParameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->get("threads/{$threadId}/messages", $queryParameters, $baseUrlOverride);
    }
}

