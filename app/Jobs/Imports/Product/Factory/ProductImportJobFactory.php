<?php

namespace App\Jobs\Imports\Product\Factory;

use App\Jobs\Imports\Factory\Contracts\ImportFactoryJobInterface;
use App\Jobs\Imports\Product\ProductImportJob;
use App\Models\ProcessedFile;

class ProductImportJobFactory implements ImportFactoryJobInterface
{
    /**
     * @param ProcessedFile $processedFile
     *
     * @return ProductImportJob
     */
    public function create(ProcessedFile $processedFile): ProductImportJob
    {
        return new ProductImportJob($processedFile);
    }
}
