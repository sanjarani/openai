<?php

namespace Sanjarani\OpenAI\Resources;

use Sanjarani\OpenAI\Contracts\OpenAIClientContract;

class Images extends AbstractResource
{
    /**
     * Creates an image given a prompt.
     *
     * @param array $parameters
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/images/create
     */
    public function create(array $parameters, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("images/generations", $parameters, $baseUrlOverride);
    }

    /**
     * Creates an edited or extended image given an original image and a prompt.
     *
     * @param array $parameters 
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/images/createEdit
     */
    public function createEdit(array $parameters, ?string $baseUrlOverride = null): array
    {
        $multipartData = [];
        foreach ($parameters as $key => $value) {
            if ($key === "image" || $key === "mask") {
                if (is_string($value)) {
                    $multipartData[] = ["name" => $key, "contents" => fopen($value, "r")];
                } elseif (is_resource($value)) {
                    $multipartData[] = ["name" => $key, "contents" => $value];
                }
            } else {
                $multipartData[] = ["name" => $key, "contents" => $value];
            }
        }
        return $this->client->multipartPost("images/edits", $multipartData, $baseUrlOverride);
    }

    /**
     * Creates a variation of a given image.
     *
     * @param array $parameters 
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/images/createVariation
     */
    public function createVariation(array $parameters, ?string $baseUrlOverride = null): array
    {
        $multipartData = [];
        foreach ($parameters as $key => $value) {
            if ($key === "image") {
                if (is_string($value)) {
                    $multipartData[] = ["name" => $key, "contents" => fopen($value, "r")];
                } elseif (is_resource($value)) {
                    $multipartData[] = ["name" => $key, "contents" => $value];
                }
            } else {
                $multipartData[] = ["name" => $key, "contents" => $value];
            }
        }
        return $this->client->multipartPost("images/variations", $multipartData, $baseUrlOverride);
    }
}

