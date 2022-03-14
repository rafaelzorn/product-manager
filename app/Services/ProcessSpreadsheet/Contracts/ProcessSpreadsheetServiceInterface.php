<?php

namespace App\Services\ProcessSpreadsheet\Contracts;

use Illuminate\Http\UploadedFile;
use App\Models\ProcessedFile;
use App\Jobs\Imports\Factory\Contracts\ImportFactoryJobInterface;

interface ProcessSpreadsheetServiceInterface
{
    /**
     * @param UploadedFile $spreadsheet
     * @param ImportFactoryJobInterface $importFactoryJob
     *
     * @return ProcessedFile
     */
    public function process(UploadedFile $spreadsheet, ImportFactoryJobInterface $importFactoryJob): ProcessedFile;
}
