<?php

namespace App\Services\ProcessSpreadsheet\Contracts;

use Illuminate\Http\UploadedFile;
use App\Models\ProcessedFile;
use App\Jobs\Imports\Factory\ImportFactoryJob;

interface ProcessSpreadsheetServiceInterface
{
    /**
     * @param UploadedFile $spreadsheet
     * @param ImportFactoryJob $importFactoryJob
     *
     * @return ProcessedFile
     */
    public function process(UploadedFile $spreadsheet, ImportFactoryJob $importFactoryJob): ProcessedFile;
}
