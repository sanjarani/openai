<?php

namespace Sanjarani\OpenAI\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Utils;
use Sanjarani\OpenAI\Contracts\OpenAIClientContract;
use Sanjarani\OpenAI\Exceptions\ApiException;
use Sanjarani\OpenAI\Exceptions\AuthenticationException;
use Sanjarani\OpenAI\Exceptions\InvalidRequestException;
use Sanjarani\OpenAI\Exceptions\RateLimitException;
use Sanjarani\OpenAI\Exceptions\ServerErrorException;
use Psr\Http\Message\ResponseInterface;

class Client implements OpenAIClientContract
{
    protected GuzzleClient $defaultClient;
    protected string $apiKey;
    protected ?string $organizationId;
    protected array $config;
    protected string $defaultBaseUrl;

    public function __construct(string $apiKey, ?string $organizationId = null, array $config = [])
    {
        $this->apiKey = $apiKey;
        $this->organizationId = $organizationId;
        $this->config = $config;
        $this->defaultBaseUrl = $this->config["base_url"] ?? "https://api.openai.com/v1/";

        $httpSettings = $this->config["http"] ?? [];
        $this->defaultClient = new GuzzleClient([
            "headers" => $this->buildHeaders(), // Default headers, base_uri will be set per request or use default
            "timeout" => $httpSettings["timeout"] ?? 30,
            "connect_timeout" => $httpSettings["connect_timeout"] ?? 10,
            "http_errors" => false, // We will handle HTTP errors manually
        ]);
    }

    protected function getHttpClient(?string $baseUrlOverride = null): GuzzleClient
    {
        if ($baseUrlOverride && $baseUrlOverride !== $this->defaultBaseUrl) {
            // Create a new client instance if base URL is different
            // This is to ensure different base URLs don't interfere with Guzzle's internal connection pooling for the default base URL
            $httpSettings = $this->config["http"] ?? [];
            return new GuzzleClient([
                "headers" => $this->buildHeaders(),
                "timeout" => $httpSettings["timeout"] ?? 30,
                "connect_timeout" => $httpSettings["connect_timeout"] ?? 10,
                "http_errors" => false,
            ]);
        }
        return $this->defaultClient;
    }

    protected function buildHeaders(bool $isMultipart = false): array
    {
        $headers = [
            "Authorization" => "Bearer " . $this->apiKey,
            "Accept" => "application/json",
        ];
        if (!$isMultipart) {
            $headers["Content-Type"] = "application/json";
        }
        if ($this->organizationId) {
            $headers["OpenAI-Organization"] = $this->organizationId;
        }
        // Add OpenAI-Beta headers if specified in config (e.g., for Assistants v2)
        if (!empty($this->config["openai_beta_headers"]) && is_array($this->config["openai_beta_headers"])) {
            foreach ($this->config["openai_beta_headers"] as $key => $value) {
                $headers[$key] = $value;
            }
        }
        $headers['OpenAI-Beta'] = 'assistants=v2';

        return $headers;
    }

    public function get(string $uri, array $query = [], ?string $baseUrlOverride = null): array|string
    {
        if (isset($query["file_id_for_content"]) && $uri === "files/{$query["file_id_for_content"]}/content") {
            unset($query["file_id_for_content"]);
            return $this->requestRaw("GET", $uri, ["query" => $query], [], $baseUrlOverride);
        }

        return $this->requestJson("GET", $uri, ["query" => $query], false, $baseUrlOverride);
    }

    public function post(string $uri, array $data = [], ?string $baseUrlOverride = null): array|string
    {
        if ($uri === "audio/speech") {
            return $this->requestRaw("POST", $uri, ["json" => $data], ["Accept" => "application/octet-stream"], $baseUrlOverride);
        }
        return $this->requestJson("POST", $uri, ["json" => $data], false, $baseUrlOverride);
    }

    public function delete(string $uri, ?string $baseUrlOverride = null): array
    {
        return $this->requestJson("DELETE", $uri, [], false, $baseUrlOverride);
    }

    public function multipartPost(string $uri, array $multipartData = [], ?string $baseUrlOverride = null): array
    {
        return $this->requestJson("POST", $uri, ["multipart" => $multipartData], true, $baseUrlOverride);
    }

    public function stream(string $uri, array $data, callable $callback, ?string $baseUrlOverride = null): void
    {
        $options = [
            "json" => array_merge($data, ["stream" => true]),
            "stream" => true,
        ];
        $httpSettings = $this->config["http"] ?? [];
        $maxRetries = $httpSettings["retry_max_attempts"] ?? 1;
        $retryDelay = $httpSettings["retry_delay_ms"] ?? 1000;
        $client = $this->getHttpClient($baseUrlOverride);
        $requestUrl = ($baseUrlOverride ?? $this->defaultBaseUrl) . $uri;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $response = $client->request("POST", $requestUrl, array_merge(["headers" => $this->buildHeaders()], $options));
                $this->handleStreamResponse($response, $callback);
                return;
            } catch (RequestException $e) {
                if ($attempt < $maxRetries && $this->shouldRetry($e)) {
                    usleep($retryDelay * 1000);
                    continue;
                }
                $this->handleRequestException($e);
            }
        }
        throw new ApiException("Max retries reached for stream or unhandled error.");
    }

    protected function requestJson(string $method, string $uri, array $options = [], bool $isMultipart = false, ?string $baseUrlOverride = null): array
    {
        $response = $this->performRequest($method, $uri, $options, $isMultipart, [], $baseUrlOverride);
        return $this->handleJsonResponse($response);
    }

    protected function requestRaw(string $method, string $uri, array $options = [], array $additionalHeaders = [], ?string $baseUrlOverride = null): string
    {
        $response = $this->performRequest($method, $uri, $options, false, $additionalHeaders, $baseUrlOverride);
        return $this->handleRawResponse($response);
    }

    protected function performRequest(string $method, string $uri, array $options = [], bool $isMultipart = false, array $additionalHeaders = [], ?string $baseUrlOverride = null): ResponseInterface
    {
        $httpSettings = $this->config["http"] ?? [];
        $maxRetries = $httpSettings["retry_max_attempts"] ?? 1;
        $retryDelay = $httpSettings["retry_delay_ms"] ?? 1000;
        $client = $this->getHttpClient($baseUrlOverride);
        $requestUrl = ($baseUrlOverride ?? $this->defaultBaseUrl) . $uri;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $requestOptions = array_merge(["headers" => array_merge($this->buildHeaders($isMultipart), $additionalHeaders)], $options);
                return $client->request($method, $requestUrl, $requestOptions);
            } catch (RequestException $e) {
                if ($attempt < $maxRetries && $this->shouldRetry($e)) {
                    usleep($retryDelay * 1000);
                    continue;
                }
                $this->handleRequestException($e);
            }
        }
        throw new ApiException("Max retries reached or unhandled error.");
    }

    protected function handleJsonResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        if ($statusCode >= 200 && $statusCode < 300) {
            return $data ?? [];
        }
        $this->throwSpecificException($statusCode, $body, $data);
    }

    protected function handleRawResponse(ResponseInterface $response): string
    {
        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();

        if ($statusCode >= 200 && $statusCode < 300) {
            return $body;
        }
        $data = json_decode($body, true);
        $this->throwSpecificException($statusCode, $body, $data);
    }

    protected function throwSpecificException(int $statusCode, string $rawBody, ?array $decodedBody): void
    {
        $errorMessage = $decodedBody["error"]["message"] ?? $rawBody ?? "Unknown API error";
        $errorCode = $decodedBody["error"]["code"] ?? null;
        $errorType = $decodedBody["error"]["type"] ?? null;

        match ($statusCode) {
            400 => throw new InvalidRequestException($errorMessage, $statusCode, $errorType, $errorCode),
            401 => throw new AuthenticationException($errorMessage, $statusCode, $errorType, $errorCode),
            429 => throw new RateLimitException($errorMessage, $statusCode, $errorType, $errorCode),
            500, 502, 503, 504 => throw new ServerErrorException($errorMessage, $statusCode, $errorType, $errorCode),
            default => throw new ApiException($errorMessage, $statusCode, $errorType, $errorCode),
        };
    }

    protected function handleStreamResponse(ResponseInterface $response, callable $callback): void
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            $this->throwSpecificException($statusCode, $body, $data);
        }

        $body = $response->getBody();
        while (!$body->eof()) {
            $line = Utils::readLine($body);
            if (empty($line)) continue;

            if (str_starts_with($line, "data: ")) {
                $jsonData = substr($line, 6);
                if (trim($jsonData) === "[DONE]") {
                    return;
                }
                $decoded = json_decode($jsonData, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $callback($decoded);
                }
            }
        }
    }

    protected function handleRequestException(RequestException $e): void
    {
        if ($e->hasResponse()) {
            $this->handleJsonResponse($e->getResponse());
        } else {
            throw new ApiException("Network error: " . $e->getMessage(), $e->getCode(), null, null, $e);
        }
    }

    protected function shouldRetry(RequestException $e): bool
    {
        if ($e->hasResponse()) {
            $statusCode = $e->getResponse()->getStatusCode();
            return $statusCode >= 500 || $statusCode == 429;
        }
        return true;
    }
}

