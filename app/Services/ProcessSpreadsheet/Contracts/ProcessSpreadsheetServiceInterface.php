<?php

namespace App\Services\ProcessSpreadsheet\Contracts;

use Illuminate\Http\UploadedFile;
use App\Models\ProcessedFile;
use App\Imports\Queued\ImportQueuedInterface;

interface ProcessSpreadsheetServiceInterface
{
    /**
     * @param UploadedFile $spreadsheet
     * @param ImportQueuedInterface $import
     *
     * @return ProcessedFile
     */
    public function process(UploadedFile $spreadsheet, ImportQueuedInterface $importQueued): ProcessedFile;
}
