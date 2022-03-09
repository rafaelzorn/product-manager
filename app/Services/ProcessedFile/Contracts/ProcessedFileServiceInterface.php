<?php

namespace App\Services\ProcessedFile\Contracts;

interface ProcessedFileServiceInterface
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function getProcessedFile(int $id): array;
}
