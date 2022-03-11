<?php

namespace App\Services\ProcessSpreadsheet;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use App\Services\ProcessSpreadsheet\Contracts\ProcessSpreadsheetServiceInterface;
use App\Models\ProcessedFile;
use App\Repositories\ProcessedFile\Contracts\ProcessedFileRepositoryInterface;
use App\Constants\StoragePathConstant;
use App\Imports\Queued\ImportQueuedInterface;

class ProcessSpreadsheetService implements ProcessSpreadsheetServiceInterface
{
    /**
     * @var ProcessedFileRepositoryInterface
     */
    private $processedFileRepository;

    /**
     * @var Excel
     */
    private $excel;

    /**
     * @param ProcessedFileRepositoryInterface $processedFileRepository
     * @param Excel $excel
     *
     * @return void
     */
    public function __construct(
        ProcessedFileRepositoryInterface $processedFileRepository,
        Excel $excel
    )
    {
        $this->processedFileRepository = $processedFileRepository;
        $this->excel                   = $excel;
    }

    /**
     * @param UploadedFile $spreadsheet
     * @param ImportQueuedInterface $import
     *
     * @return ProcessedFile
     */
    public function process(UploadedFile $spreadsheet, ImportQueuedInterface $importQueued): ProcessedFile
    {
        $originalFileName = $spreadsheet->getClientOriginalName();
        $storedFileName   = $spreadsheet->store(StoragePathConstant::IMPORTED_SPREADSHEETS);

        $processedFile = $this->processedFileRepository->create([
            'original_filename' => $originalFileName,
            'stored_filename'   => $storedFileName
        ]);

        $importQueued->setProcessedFile($processedFile);

        $this->excel->import(
            $importQueued,
            $processedFile->stored_filename
        );

        return $processedFile;
    }
}
