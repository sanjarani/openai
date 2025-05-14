<?php

namespace Sanjarani\OpenAI\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class CacheManager
{
    protected array $config;
    protected ?CacheRepository $store = null;

    public function __construct(array $config)
    {
        $this->config = $config;
        if ($this->isEnabled()) {
            $this->store = Cache::store($this->getStoreName());
        }
    }

    public function isEnabled(): bool
    {
        return (bool) ($this->config["caching"]["enabled"] ?? false);
    }

    protected function getStoreName(): ?string
    {
        return $this->config["caching"]["store"] ?? null;
    }

    protected function getPrefix(): string
    {
        return $this->config["caching"]["prefix"] ?? "openai_api_cache";
    }

    protected function getDefaultTtl(): int
    {
        return (int) ($this->config["caching"]["ttl"] ?? 3600); // Default 1 hour
    }

    protected function getEndpointTtl(string $endpointKey): ?int
    {
        $ttl = $this->config["caching"]["endpoints"][$endpointKey] ?? $this->getDefaultTtl();
        return is_null($ttl) ? null : (int) $ttl; // null means cache forever, 0 means don't cache for this specific endpoint if default is enabled
    }

    protected function buildCacheKey(string $endpointKey, array $parameters): string
    {
        // Create a stable hash from parameters
        // Exclude any parameters that should not affect the cache key, e.g., stream flag if handled separately
        unset($parameters["stream"]); 
        $serializedParameters = http_build_query($parameters); // More stable than json_encode for key generation
        return $this->getPrefix() . "_" . $endpointKey . "_" . sha1($serializedParameters);
    }

    public function get(string $endpointKey, array $parameters, callable $fetchCallback)
    {
        if (!$this->isEnabled() || !$this->store) {
            return $fetchCallback();
        }

        $ttl = $this->getEndpointTtl($endpointKey);
        if ($ttl === 0) { // 0 TTL means explicitly don't cache this endpoint
            return $fetchCallback();
        }

        $cacheKey = $this->buildCacheKey($endpointKey, $parameters);

        if ($this->store->has($cacheKey)) {
            return $this->store->get($cacheKey);
        }

        $result = $fetchCallback();

        if ($ttl === null) { // Cache forever
            $this->store->forever($cacheKey, $result);
        } elseif ($ttl > 0) {
            $this->store->put($cacheKey, $result, $ttl);
        }
        // If TTL is somehow negative or invalid, it won't cache, which is fine.

        return $result;
    }

    public function forget(string $endpointKey, array $parameters): bool
    {
        if (!$this->isEnabled() || !$this->store) {
            return false;
        }
        $cacheKey = $this->buildCacheKey($endpointKey, $parameters);
        return $this->store->forget($cacheKey);
    }

    public function flush(): bool
    {
        if (!$this->isEnabled() || !$this->store) {
            return false;
        }
        // Note: Flushing might be too broad. Laravel's cache store `flush` clears everything in that store.
        // If a dedicated prefix is used, and the store supports tag-based caching, that would be better.
        // For simplicity, this flushes the entire configured cache store if called.
        // A more granular approach would involve iterating keys with the prefix, which is store-dependent.
        return $this->store->flush(); // Be cautious with this in production if the cache store is shared.
    }
}

