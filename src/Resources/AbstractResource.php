<?php

namespace Sanjarani\OpenAI\Resources;

use Sanjarani\OpenAI\Contracts\OpenAIClientContract;

abstract class AbstractResource
{
    protected OpenAIClientContract $client;
    protected array $config;

    public function __construct(OpenAIClientContract $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
    }
}

