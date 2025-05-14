<?php

namespace Sanjarani\OpenAI\Resources;

use Sanjarani\OpenAI\Contracts\OpenAIClientContract;

class Audio extends AbstractResource
{
    /**
     * Transcribes audio into the input language.
     *
     * @param array $parameters
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/audio/createTranscription
     */
    public function createTranscription(array $parameters, ?string $baseUrlOverride = null): array
    {
        if (!isset($parameters["model"])) {
            $parameters["model"] = $this->config["defaults"]["audio_transcription"] ?? "whisper-1";
        }
        return $this->client->multipartPost("audio/transcriptions", $this->prepareMultipartData($parameters), $baseUrlOverride);
    }

    /**
     * Translates audio into English.
     *
     * @param array $parameters
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/audio/createTranslation
     */
    public function createTranslation(array $parameters, ?string $baseUrlOverride = null): array
    {
        if (!isset($parameters["model"])) {
            $parameters["model"] = $this->config["defaults"]["audio_translation"] ?? "whisper-1";
        }
        return $this->client->multipartPost("audio/translations", $this->prepareMultipartData($parameters), $baseUrlOverride);
    }

    /**
     * Generates audio from the input text.
     *
     * @param array $parameters
     * @param string|null $baseUrlOverride
     * @return string The audio content as a binary string.
     * @see https://platform.openai.com/docs/api-reference/audio/createSpeech
     */
    public function createSpeech(array $parameters, ?string $baseUrlOverride = null): string
    {
        if (!isset($parameters["model"])) {
            $parameters["model"] = $this->config["defaults"]["audio_speech"] ?? "tts-1"; // Assuming a default
        }
        if (!isset($parameters["voice"])) {
            $parameters["voice"] = $this->config["defaults"]["audio_speech_voice"] ?? "alloy"; // Assuming a default
        }
        // The client->post method will handle the raw response for "audio/speech"
        return $this->client->post("audio/speech", $parameters, $baseUrlOverride);
    }

    /**
     * Helper to prepare multipart data, converting file paths to resources.
     */
    protected function prepareMultipartData(array $parameters): array
    {
        $multipartData = [];
        foreach ($parameters as $key => $value) {
            if ($key === "file") {
                if (is_string($value)) { // If it is a path
                    $multipartData[] = ["name" => $key, "contents" => fopen($value, "r"), "filename" => basename($value)];
                } elseif (is_resource($value)) { // If it is already a resource
                    $meta = stream_get_meta_data($value);
                    $filename = isset($meta["uri"]) ? basename($meta["uri"]) : "audio.tmp";
                    $multipartData[] = ["name" => $key, "contents" => $value, "filename" => $filename];
                }
            } else {
                $multipartData[] = ["name" => $key, "contents" => $value];
            }
        }
        return $multipartData;
    }
}

