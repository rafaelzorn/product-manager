<?php

namespace App\Imports\Base;

use Exception;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\ImportFailed;
use App\Repositories\ProcessedFileLog\Contracts\ProcessedFileLogRepositoryInterface;
use App\Models\ProcessedFile;
use App\Enums\ProcessedFileStatusEnum;

abstract class BaseImport implements ToCollection, WithMultipleSheets, WithEvents, WithHeadingRow, WithValidation
{
    /**
     * @var ProcessedFile
     */
    private $processedFile;

    /**
     * @var ProcessedFileLogRepositoryInterface
     */
    protected $processedFileLogRepository;

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
     * @param ImportFailed $event
     *
     * @return void
     */
    protected function importFailed(ImportFailed $event): void
    {
        $this->deleteProcessedFile();

        $this->updateStatusFileProcessed(ProcessedFileStatusEnum::Failed);

        $this->saveLogException($event->getException());
    }

    /**
     * @param Exception $exception
     *
     * @return void
     */
    private function saveLogException(Exception $exception): void
    {
        $this->processedFileLogRepository->create([
            'processed_file_id' => $this->processedFile->id,
            'exception_message' => $exception->getMessage(),
            'exception_trace'   => $exception->getTraceAsString(),
        ]);
    }

    /**
     * @return void
     */
    private function deleteProcessedFile(): void
    {
        $filePath = $this->processedFile->stored_filename;

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }
}
