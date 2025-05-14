<?php

namespace Sanjarani\OpenAI\Resources\Assistants;

use Sanjarani\OpenAI\Resources\AbstractResource;

class Runs extends AbstractResource
{
    /**
     * Create a run.
     *
     * @param string $threadId
     * @param array $parameters Requires: assistant_id. Optional: model, instructions, additional_instructions, additional_messages, tools, metadata, stream, max_prompt_tokens, max_completion_tokens, truncation_strategy, tool_choice, response_format
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/runs/createRun
     */
    public function create(string $threadId, array $parameters, ?string $baseUrlOverride = null): array
    {
        if (isset($parameters["stream"]) && $parameters["stream"] === true) {
            // This should be handled by a dedicated stream method if that's the pattern
        }
        return $this->client->post("threads/{$threadId}/runs", $parameters, $baseUrlOverride);
    }

    /**
     * Create a thread and run it in one request.
     *
     * @param array $parameters Requires: assistant_id. Optional: thread, model, instructions, tools, metadata, stream, etc.
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/runs/createThreadAndRun
     */
    public function createThreadAndRun(array $parameters, ?string $baseUrlOverride = null): array
    {
         if (isset($parameters["stream"]) && $parameters["stream"] === true) {
            // This should be handled by a dedicated stream method
        }
        return $this->client->post("threads/runs", $parameters, $baseUrlOverride);
    }

    /**
     * Retrieves a run.
     *
     * @param string $threadId
     * @param string $runId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/runs/getRun
     */
    public function retrieve(string $threadId, string $runId, ?string $baseUrlOverride = null): array
    {
        return $this->client->get("threads/{$threadId}/runs/{$runId}", [], $baseUrlOverride);
    }

    /**
     * Modifies a run.
     *
     * @param string $threadId
     * @param string $runId
     * @param array $parameters Optional: metadata
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/runs/modifyRun
     */
    public function modify(string $threadId, string $runId, array $parameters, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("threads/{$threadId}/runs/{$runId}", $parameters, $baseUrlOverride);
    }

    /**
     * Returns a list of runs belonging to a thread.
     *
     * @param string $threadId
     * @param array $queryParameters Optional: limit, order, after, before
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/runs/listRuns
     */
    public function list(string $threadId, array $queryParameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->get("threads/{$threadId}/runs", $queryParameters, $baseUrlOverride);
    }

    /**
     * When a run has the status: "requires_action" and required_action.type is "submit_tool_outputs", 
     * this endpoint can be used to submit the outputs from the tool calls once they're all complete. 
     * All outputs must be submitted in a single request.
     *
     * @param string $threadId
     * @param string $runId
     * @param array $parameters Requires: tool_outputs. Optional: stream
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/runs/submitToolOutputs
     */
    public function submitToolOutputs(string $threadId, string $runId, array $parameters, ?string $baseUrlOverride = null): array
    {
        if (isset($parameters["stream"]) && $parameters["stream"] === true) {
            // This should be handled by a dedicated stream method
        }
        return $this->client->post("threads/{$threadId}/runs/{$runId}/submit_tool_outputs", $parameters, $baseUrlOverride);
    }

    /**
     * Cancels a run that is "in_progress".
     *
     * @param string $threadId
     * @param string $runId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/runs/cancelRun
     */
    public function cancel(string $threadId, string $runId, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("threads/{$threadId}/runs/{$runId}/cancel", [], $baseUrlOverride);
    }

    /**
     * Returns a list of run steps belonging to a run.
     *
     * @param string $threadId
     * @param string $runId
     * @param array $queryParameters Optional: limit, order, after, before
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/run-steps/listRunSteps
     */
    public function listSteps(string $threadId, string $runId, array $queryParameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->get("threads/{$threadId}/runs/{$runId}/steps", $queryParameters, $baseUrlOverride);
    }

    /**
     * Retrieves a run step.
     *
     * @param string $threadId
     * @param string $runId
     * @param string $stepId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/run-steps/getRunStep
     */
    public function retrieveStep(string $threadId, string $runId, string $stepId, ?string $baseUrlOverride = null): array
    {
        return $this->client->get("threads/{$threadId}/runs/{$runId}/steps/{$stepId}", [], $baseUrlOverride);
    }

    // Streaming methods for Runs

    /**
     * Stream a run.
     *
     * @param string $threadId
     * @param array $parameters
     * @param callable $callback
     * @param string|null $baseUrlOverride
     * @return void
     */
    public function stream(string $threadId, array $parameters, callable $callback, ?string $baseUrlOverride = null): void
    {
        $parameters["stream"] = true;
        $this->client->stream("threads/{$threadId}/runs", $parameters, $callback, $baseUrlOverride);
    }

    /**
     * Stream a thread and run it in one request.
     *
     * @param array $parameters
     * @param callable $callback
     * @param string|null $baseUrlOverride
     * @return void
     */
    public function streamThreadAndRun(array $parameters, callable $callback, ?string $baseUrlOverride = null): void
    {
        $parameters["stream"] = true;
        $this->client->stream("threads/runs", $parameters, $callback, $baseUrlOverride);
    }

    /**
     * Stream tool outputs submission.
     *
     * @param string $threadId
     * @param string $runId
     * @param array $parameters
     * @param callable $callback
     * @param string|null $baseUrlOverride
     * @return void
     */
    public function streamToolOutputs(string $threadId, string $runId, array $parameters, callable $callback, ?string $baseUrlOverride = null): void
    {
        $parameters["stream"] = true;
        $this->client->stream("threads/{$threadId}/runs/{$runId}/submit_tool_outputs", $parameters, $callback, $baseUrlOverride);
    }
}

