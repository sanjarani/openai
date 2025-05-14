<?php

namespace Sanjarani\OpenAI\Resources;

use Sanjarani\OpenAI\Contracts\OpenAIClientContract;

class Files extends AbstractResource
{
    /**
     * Returns a list of files that belong to the user's organization.
     *
     * @param array $queryParameters Optional: purpose
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/files/list
     */
    public function list(array $queryParameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->get("files", $queryParameters, $baseUrlOverride);
    }

    /**
     * Upload a file that contains document(s) to be used across various endpoints/features.
     *
     * @param array $parameters Requires: file (file path or resource), purpose
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/files/create
     */
    public function create(array $parameters, ?string $baseUrlOverride = null): array
    {
        $multipartData = [];
        foreach ($parameters as $key => $value) {
            if ($key === "file") {
                if (is_string($value)) { // If it is a path
                    $multipartData[] = ["name" => $key, "contents" => fopen($value, "r"), "filename" => basename($value)];
                } elseif (is_resource($value)) { // If it is already a resource
                    $meta = stream_get_meta_data($value);
                    $filename = isset($meta["uri"]) ? basename($meta["uri"]) : "file.tmp";
                    $multipartData[] = ["name" => $key, "contents" => $value, "filename" => $filename];
                }
            } else {
                $multipartData[] = ["name" => $key, "contents" => $value];
            }
        }
        return $this->client->multipartPost("files", $multipartData, $baseUrlOverride);
    }

    /**
     * Delete a file.
     *
     * @param string $fileId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/files/delete
     */
    public function delete(string $fileId, ?string $baseUrlOverride = null): array
    {
        return $this->client->delete("files/{$fileId}", $baseUrlOverride);
    }

    /**
     * Returns information about a specific file.
     *
     * @param string $fileId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/files/retrieve
     */
    public function retrieve(string $fileId, ?string $baseUrlOverride = null): array
    {
        return $this->client->get("files/{$fileId}", [], $baseUrlOverride);
    }

    /**
     * Returns the contents of the specified file.
     *
     * @param string $fileId
     * @param string|null $baseUrlOverride
     * @return string
     * @see https://platform.openai.com/docs/api-reference/files/retrieve-content
     */
    public function retrieveContent(string $fileId, ?string $baseUrlOverride = null): string
    {
        // The client->get method will handle the raw response for this specific URI pattern
        return $this->client->get("files/{$fileId}/content", ["file_id_for_content" => $fileId], $baseUrlOverride);
    }
}

