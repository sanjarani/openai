<?php

namespace Sanjarani\OpenAI\Contracts;

interface OpenAIClientContract
{
    /**
     * Make a GET request to the OpenAI API.
     *
     * @param string $uri
     * @param array $query
     * @param string|null $baseUrlOverride
     * @return array|string
     */
    public function get(string $uri, array $query = [], ?string $baseUrlOverride = null): array|string;

    /**
     * Make a POST request to the OpenAI API.
     *
     * @param string $uri
     * @param array $data
     * @param string|null $baseUrlOverride
     * @return array|string
     */
    public function post(string $uri, array $data = [], ?string $baseUrlOverride = null): array|string;

    /**
     * Make a DELETE request to the OpenAI API.
     *
     * @param string $uri
     * @param string|null $baseUrlOverride
     * @return array
     */
    public function delete(string $uri, ?string $baseUrlOverride = null): array;

    /**
     * Make a multipart POST request to the OpenAI API (for file uploads).
     *
     * @param string $uri
     * @param array $multipartData
     * @param string|null $baseUrlOverride
     * @return array
     */
    public function multipartPost(string $uri, array $multipartData = [], ?string $baseUrlOverride = null): array;

    /**
     * Make a streaming POST request to the OpenAI API.
     *
     * @param string $uri
     * @param array $data
     * @param callable $callback
     * @param string|null $baseUrlOverride
     * @return void
     */
    public function stream(string $uri, array $data, callable $callback, ?string $baseUrlOverride = null): void;
}

