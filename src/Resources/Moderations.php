<?php

namespace Sanjarani\OpenAI\Resources;

use Sanjarani\OpenAI\Contracts\OpenAIClientContract;

class Moderations extends AbstractResource
{
    /**
     * Classifies if text violates OpenAI's Content Policy.
     *
     * @param array $parameters
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/moderations/create
     */
    public function create(array $parameters, ?string $baseUrlOverride = null): array
    {
        // Ensure default model is set if not provided and if applicable for moderations
        // if (!isset($parameters["model"])) {
        //     $parameters["model"] = $this->config["defaults"]["moderations"] ?? "text-moderation-latest";
        // }
        return $this->client->post("moderations", $parameters, $baseUrlOverride);
    }
}

