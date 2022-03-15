<?php

namespace App\Jobs\Imports\Product;

use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\Imports\Base\BaseImportJob;
use App\Models\ProcessedFile;
use App\Imports\Product\ProductImport;
use App\Repositories\ProcessedFileLog\Contracts\ProcessedFileLogRepositoryInterface;

class ProductImportJob extends BaseImportJob
{
    /**
     * @var ProductImport
     */
    private $productImport;

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
    public function handle(
        ProductImport $productImport,
        ProcessedFileLogRepositoryInterface $processedFileLogRepository
    ): void
    {
        $this->productImport              = $productImport;
        $this->processedFileLogRepository = $processedFileLogRepository;

        $this->import();
    }

    /**
     * @return void
     */
    private function import(): void
    {
        try {
            DB::beginTransaction();

            $this->productImport->setProcessedFile($this->processedFile);

            Excel::import(
                $this->productImport,
                $this->processedFile->stored_filename
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            $this->failedImport($e);
        }
    }
}
