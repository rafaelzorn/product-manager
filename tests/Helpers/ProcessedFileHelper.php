<?php

namespace Tests\Helpers;

use App\Models\ProcessedFile;

class ProcessedFileHelper
{
    /**
     * @param string $originalFileName
     * @param string $stubFilePath
     * @param string $storePath
     *
     * @return ProcessedFile $processedFile
     */
    public static function createProcessedFile(string $originalFileName, string $stubFilePath, string $storePath): ProcessedFile
    {
        $stubFilePath = "{$stubFilePath}{$originalFileName}";

        $processedFile = ProcessedFile::factory()
                            ->storedFile($originalFileName, $stubFilePath, $storePath)
                            ->create();

        return $processedFile;
    }
}
