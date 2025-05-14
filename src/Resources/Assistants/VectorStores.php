<?php

namespace Sanjarani\OpenAI\Resources\Assistants;

use Sanjarani\OpenAI\Resources\AbstractResource;

class VectorStores extends AbstractResource
{
    /**
     * Create a vector store.
     *
     * @param array $parameters Optional: name, expires_after, file_ids, metadata
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores/create
     */
    public function create(array $parameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->post("vector_stores", $parameters, $baseUrlOverride);
    }

    /**
     * Retrieves a vector store.
     *
     * @param string $vectorStoreId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores/retrieve
     */
    public function retrieve(string $vectorStoreId, ?string $baseUrlOverride = null): array
    {
        return $this->client->get("vector_stores/{$vectorStoreId}", [], $baseUrlOverride);
    }

    /**
     * Modifies a vector store.
     *
     * @param string $vectorStoreId
     * @param array $parameters Optional: name, expires_after, metadata
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores/modify
     */
    public function modify(string $vectorStoreId, array $parameters, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("vector_stores/{$vectorStoreId}", $parameters, $baseUrlOverride);
    }

    /**
     * Deletes a vector store.
     *
     * @param string $vectorStoreId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores/delete
     */
    public function delete(string $vectorStoreId, ?string $baseUrlOverride = null): array
    {
        return $this->client->delete("vector_stores/{$vectorStoreId}", $baseUrlOverride);
    }

    /**
     * Returns a list of vector stores.
     *
     * @param array $queryParameters Optional: limit, order, after, before
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores/list
     */
    public function list(array $queryParameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->get("vector_stores", $queryParameters, $baseUrlOverride);
    }

    // Vector Store Files

    /**
     * Create a vector store file by attaching a File to a vector store.
     *
     * @param string $vectorStoreId
     * @param array $parameters Requires: file_id
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores-files/createFile
     */
    public function createFile(string $vectorStoreId, array $parameters, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("vector_stores/{$vectorStoreId}/files", $parameters, $baseUrlOverride);
    }

    /**
     * Retrieves a vector store file.
     *
     * @param string $vectorStoreId
     * @param string $fileId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores-files/getFile
     */
    public function retrieveFile(string $vectorStoreId, string $fileId, ?string $baseUrlOverride = null): array
    {
        return $this->client->get("vector_stores/{$vectorStoreId}/files/{$fileId}", [], $baseUrlOverride);
    }

    /**
     * Deletes a vector store file. This will remove the file from the vector store but the file itself will not be deleted.
     *
     * @param string $vectorStoreId
     * @param string $fileId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores-files/deleteFile
     */
    public function deleteFile(string $vectorStoreId, string $fileId, ?string $baseUrlOverride = null): array
    {
        return $this->client->delete("vector_stores/{$vectorStoreId}/files/{$fileId}", $baseUrlOverride);
    }

    /**
     * Returns a list of vector store files.
     *
     * @param string $vectorStoreId
     * @param array $queryParameters Optional: limit, order, after, before, filter
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores-files/listFiles
     */
    public function listFiles(string $vectorStoreId, array $queryParameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->get("vector_stores/{$vectorStoreId}/files", $queryParameters, $baseUrlOverride);
    }
    
    // Vector Store File Batches

    /**
     * Create a vector store file batch.
     *
     * @param string $vectorStoreId
     * @param array $parameters Requires: file_ids. Optional: chunking_strategy
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores-file-batches/createBatch
     */
    public function createFileBatch(string $vectorStoreId, array $parameters, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("vector_stores/{$vectorStoreId}/file_batches", $parameters, $baseUrlOverride);
    }

    /**
     * Retrieves a vector store file batch.
     *
     * @param string $vectorStoreId
     * @param string $batchId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores-file-batches/getBatch
     */
    public function retrieveFileBatch(string $vectorStoreId, string $batchId, ?string $baseUrlOverride = null): array
    {
        return $this->client->get("vector_stores/{$vectorStoreId}/file_batches/{$batchId}", [], $baseUrlOverride);
    }

    /**
     * Cancel a vector store file batch. This attempts to cancel the processing of files in this batch as soon as possible.
     *
     * @param string $vectorStoreId
     * @param string $batchId
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores-file-batches/cancelBatch
     */
    public function cancelFileBatch(string $vectorStoreId, string $batchId, ?string $baseUrlOverride = null): array
    {
        return $this->client->post("vector_stores/{$vectorStoreId}/file_batches/{$batchId}/cancel", [], $baseUrlOverride);
    }

    /**
     * Returns a list of vector store files in a batch.
     *
     * @param string $vectorStoreId
     * @param string $batchId
     * @param array $queryParameters Optional: limit, order, after, before, filter
     * @param string|null $baseUrlOverride
     * @return array
     * @see https://platform.openai.com/docs/api-reference/vector-stores-file-batches/listFiles
     */
    public function listFilesInBatch(string $vectorStoreId, string $batchId, array $queryParameters = [], ?string $baseUrlOverride = null): array
    {
        return $this->client->get("vector_stores/{$vectorStoreId}/file_batches/{$batchId}/files", $queryParameters, $baseUrlOverride);
    }
}

