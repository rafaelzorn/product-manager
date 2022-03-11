<?php

namespace App\Imports\Queued;

use Illuminate\Support\Collection;
use App\Models\ProcessedFile;

interface ImportQueuedInterface
{
    /**
     * @param Collection $rows
     *
     * @return void
     */
    public function collection(Collection $rows): void;

    /**
     * @return array
     */
    public function rules(): array;

    /**
     * @return int
     */
    public function headingRow(): int;

    /**
     * @param ProcessedFile $processedFile
     *
     * @return void
     */
    public function setProcessedFile($processedFile): void;

    /**
     * @return array
     */
    public function registerEvents(): array;
}
