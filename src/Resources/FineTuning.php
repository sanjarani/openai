<?php

namespace Sanjarani\OpenAI\Resources;

use Sanjarani\OpenAI\Contracts\OpenAIClientContract;

class FineTuning extends AbstractResource
{
    /**
     * Creates a job that fine-tunes a specified model from a given dataset.
     *
     * @param array $parameters
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/fine-tuning/create
     */
    public function createJob(array $parameters, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("fine_tuning/jobs", $parameters, $baseUrlOverride);
    }

    /**
     * List your organization's fine-tuning jobs.
     *
     * @param array $queryParameters Optional: after, limit
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/fine-tuning/list
     */
    public function listJobs(array $queryParameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->get("fine_tuning/jobs", $queryParameters, $baseUrlOverride);
    }

    /**
     * Get info about a fine-tuning job.
     *
     * @param string $fineTuningJobId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/fine-tuning/retrieve
     */
    public function retrieveJob(string $fineTuningJobId, ?string $baseUrlOverride = null): array
    {
        return $this->client->get("fine_tuning/jobs/{$fineTuningJobId}", [], $baseUrlOverride);
    }

    /**
     * Immediately cancel a fine-tune job.
     *
     * @param string $fineTuningJobId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/fine-tuning/cancel
     */
    public function cancelJob(string $fineTuningJobId, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("fine_tuning/jobs/{$fineTuningJobId}/cancel", [], $baseUrlOverride);
    }

    /**
     * Get status updates from a fine-tuning job.
     *
     * @param string $fineTuningJobId
     * @param array $queryParameters Optional: after, limit
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/fine-tuning/list-events
     */
    public function listJobEvents(string $fineTuningJobId, array $queryParameters = [], ?string $baseUrlOverride = null): array
    {
        // Note: OpenAI API documentation indicates streaming is supported for events.
        // If streaming is desired, a separate streamJobEvents method should be implemented.
        return $this->client->get("fine_tuning/jobs/{$fineTuningJobId}/events", $queryParameters, $baseUrlOverride);
    }
    
    /**
     * List checkpoints for a fine-tuning job.
     *
     * @param string $fineTuningJobId
     * @param array $queryParameters Optional: after, limit
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/fine-tuning/list-checkpoints
     */
    public function listJobCheckpoints(string $fineTuningJobId, array $queryParameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->get("fine_tuning/jobs/{$fineTuningJobId}/checkpoints", $queryParameters, $baseUrlOverride);
    }
}

