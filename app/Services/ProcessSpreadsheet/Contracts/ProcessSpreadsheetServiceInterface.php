<?php

namespace App\Services\ProcessSpreadsheet\Contracts;

use Illuminate\Http\UploadedFile;
use App\Models\ProcessedFile;

interface ProcessSpreadsheetServiceInterface
{
    /**
     * @param UploadedFile $spreadsheet
     *
     * @return ProcessedFile
     */
    public function process(UploadedFile $spreadsheet): ProcessedFile;
}
