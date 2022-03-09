<?php

namespace App\Services\ProcessSpreadsheet;

use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use App\Constants\EnvironmentConstant;
use App\Services\ProcessSpreadsheet\Contracts\ProcessSpreadsheetServiceInterface;
use App\Jobs\ProcessSpreadsheetJob;
use App\Models\ProcessedFile;
use App\Repositories\ProcessedFile\Contracts\ProcessedFileRepositoryInterface;
use App\Constants\StoragePathConstant;

class ProcessSpreadsheetService implements ProcessSpreadsheetServiceInterface
{
    /**
     * @var ProcessedFileRepositoryInterface
     */
    private $processedFileRepository;

    /**
     * @param ProcessedFileRepositoryInterface $processedFileRepository
     *
     * @return void
     */
    public function __construct(ProcessedFileRepositoryInterface $processedFileRepository)
    {
        $this->processedFileRepository = $processedFileRepository;
    }

    /**
     * @param UploadedFile $spreadsheet
     *
     * @return ProcessedFile
     */
    public function process(UploadedFile $spreadsheet): ProcessedFile
    {
        $originalFileName = $spreadsheet->getClientOriginalName();
        $storedFileName   = $spreadsheet->store(StoragePathConstant::IMPORTED_SPREADSHEETS);

        $processedFile = $this->processedFileRepository->create([
            'original_filename' => $originalFileName,
            'stored_filename'   => $storedFileName
        ]);

        $this->dispatchProcessSpreadsheet($processedFile);

        return $processedFile;
    }

    /**
     * @param ProcessedFile $processedFile
     *
     * @return void
     */
    private function dispatchProcessSpreadsheet(ProcessedFile $processedFile): void
    {
        $job = new ProcessSpreadsheetJob($processedFile);

        if (config('app.app_env') == EnvironmentConstant::LOCAL) {
            $job->delay(Carbon::now()->addSeconds(10));
        }

        dispatch($job);
    }
}
