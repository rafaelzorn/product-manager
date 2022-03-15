<?php

namespace App\Jobs\Imports\Base;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\ProcessedFileLog\Contracts\ProcessedFileLogRepositoryInterface;
use App\Helpers\StorageHelper;
use App\Enums\ProcessedFileStatusEnum;
use App\Models\ProcessedFile;

abstract class BaseImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ProcessedFile
     */
    protected $processedFile;

    /**
     * @var ProcessedFileLogRepositoryInterface
     */
    protected $processedFileLogRepository;

    /**
     * @param Exception $exception
     *
     * @return void
     */
    protected function failedImport(Exception $exception): void
    {
        $this->deleteProcessedFile();

        $this->processedFile->update([
            'status' => ProcessedFileStatusEnum::Failed->value
        ]);

        $this->saveLogException($exception);
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

        StorageHelper::delete($filePath);
    }
}
