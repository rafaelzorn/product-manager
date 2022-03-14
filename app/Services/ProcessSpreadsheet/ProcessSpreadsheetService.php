<?php

namespace App\Services\ProcessSpreadsheet;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Excel;
use App\Services\ProcessSpreadsheet\Contracts\ProcessSpreadsheetServiceInterface;
use App\Models\ProcessedFile;
use App\Repositories\ProcessedFile\Contracts\ProcessedFileRepositoryInterface;
use App\Constants\StoragePathConstant;
use App\Jobs\Imports\Factory\Contracts\ImportFactoryJobInterface;

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
     * @param ImportFactoryJobInterface $importFactoryJob
     *
     * @return ProcessedFile
     */
    public function process(UploadedFile $spreadsheet, ImportFactoryJobInterface $importFactoryJob): ProcessedFile
    {
        $originalFileName = $spreadsheet->getClientOriginalName();
        $storedFileName   = $spreadsheet->store(StoragePathConstant::IMPORTED_SPREADSHEETS);

        $processedFile = $this->processedFileRepository->create([
            'original_filename' => $originalFileName,
            'stored_filename'   => $storedFileName
        ]);

        dispatch($importFactoryJob->create($processedFile));

        return $processedFile;
    }
}
