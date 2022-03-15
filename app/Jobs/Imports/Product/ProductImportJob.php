<?php

namespace App\Jobs\Imports\Product;

use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\Imports\Base\BaseImportJob;
use App\Models\ProcessedFile;
use App\Imports\Product\ProductImport;

class ProductImportJob extends BaseImportJob
{
    /**
     * @var ProductImport
     */
    private $productImport;

    /**
     * @var ProcessedFile
     */
    private $processedFile;

    /**
     * @param ProcessedFile $processedFile
     *
     * @return void
     */
    public function __construct(ProcessedFile $processedFile)
    {
        $this->processedFile = $processedFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ProductImport $productImport): void
    {
        $this->productImport = $productImport;

        $this->import();
    }

    /**
     * @return void
     */
    private function import(): void
    {
        try {
            $this->productImport->setProcessedFile($this->processedFile);

            Excel::import(
                $this->productImport,
                $this->processedFile->stored_filename
            );
        } catch (Exception $e) {}
    }
}
