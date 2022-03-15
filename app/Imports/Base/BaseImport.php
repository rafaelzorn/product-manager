<?php

namespace App\Imports\Base;

use Exception;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Helpers\StorageHelper;
use App\Models\ProcessedFile;
use App\Enums\ProcessedFileStatusEnum;

abstract class BaseImport implements ToCollection, WithMultipleSheets, WithEvents, WithHeadingRow, WithValidation
{
    /**
     * @var ProcessedFile
     */
    private $processedFile;

    /**
     * @param ProcessedFile $processedFile
     *
     * @return void
     */
    public function setProcessedFile(ProcessedFile $processedFile): void
    {
        $this->processedFile = $processedFile;
    }

    /**
     * @param ProcessedFileStatusEnum $status
     *
     * @return void
     */
    protected function updateStatusFileProcessed(ProcessedFileStatusEnum $status): void
    {
        $this->processedFile->update(['status' => $status->value]);
    }

    /**
     * @return void
     */
    protected function afterImport(): void
    {
        $this->deleteProcessedFile();

        $this->updateStatusFileProcessed(ProcessedFileStatusEnum::Completed);
    }

    /**
     * @return void
     */
    private function deleteProcessedFile(): void
    {
        $filePath = $this->processedFile->stored_filename;

        StorageHelper::delete($filePath);
    }
}
