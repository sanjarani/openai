<?php

namespace Sanjarani\OpenAI;

use Sanjarani\OpenAI\Contracts\OpenAIClientContract;
use Sanjarani\OpenAI\Resources\Chat;
use Sanjarani\OpenAI\Resources\Embeddings;
use Sanjarani\OpenAI\Resources\Images;
use Sanjarani\OpenAI\Resources\Audio;
use Sanjarani\OpenAI\Resources\Files;
use Sanjarani\OpenAI\Resources\FineTuning;
use Sanjarani\OpenAI\Resources\Moderations;
use Sanjarani\OpenAI\Resources\Models;
use Sanjarani\OpenAI\Resources\Assistants\Assistants;
use Sanjarani\OpenAI\Resources\Assistants\Threads;
use Sanjarani\OpenAI\Resources\Assistants\Messages;
use Sanjarani\OpenAI\Resources\Assistants\Runs;
use Sanjarani\OpenAI\Resources\Assistants\VectorStores;

class OpenAI
{
    protected OpenAIClientContract $client;
    protected array $config;

    public function __construct(OpenAIClientContract $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function chat(): Chat
    {
        return new Chat($this->client, $this->config);
    }

    public function embeddings(): Embeddings
    {
        return new Embeddings($this->client, $this->config);
    }

    public function images(): Images
    {
        return new Images($this->client, $this->config);
    }

    public function audio(): Audio
    {
        return new Audio($this->client, $this->config);
    }

    public function files(): Files
    {
        return new Files($this->client, $this->config);
    }

    public function fineTuning(): FineTuning
    {
        return new FineTuning($this->client, $this->config);
    }

    public function moderations(): Moderations
    {
        return new Moderations($this->client, $this->config);
    }

    public function models(): Models
    {
        return new Models($this->client, $this->config);
    }

    public function assistants(): Assistants
    {
        return new Assistants($this->client, $this->config);
    }

    public function threads(): Threads
    {
        return new Threads($this->client, $this->config);
    }

    public function messages(): Messages
    {
        return new Messages($this->client, $this->config);
    }

    public function runs(): Runs
    {
        return new Runs($this->client, $this->config);
    }

    public function vectorStores(): VectorStores
    {
        return new VectorStores($this->client, $this->config);
    }

    public function getClient(): OpenAIClientContract
    {
        return $this->client;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}

