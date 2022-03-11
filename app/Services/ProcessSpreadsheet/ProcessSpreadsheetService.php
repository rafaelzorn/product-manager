<?php

namespace App\Services\ProcessSpreadsheet;

use Illuminate\Http\UploadedFile;
use App\Services\ProcessSpreadsheet\Contracts\ProcessSpreadsheetServiceInterface;
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

        // TODO: Dispatch job

        return $processedFile;
    }
}
