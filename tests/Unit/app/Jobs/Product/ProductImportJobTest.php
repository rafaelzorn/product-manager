<?php

namespace Tests\Unit\app\Jobs\Product;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;
use App\Repositories\Category\Contracts\CategoryRepositoryInterface;
use App\Jobs\Imports\Product\ProductImportJob;
use App\Repositories\ProcessedFile\Contracts\ProcessedFileRepositoryInterface;
use App\Repositories\ProcessedFileLog\Contracts\ProcessedFileLogRepositoryInterface;
use App\Enums\ProcessedFileStatusEnum;
use Tests\Helpers\ProcessedFileHelper;

class ProductImportJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var ProcessedFileRepositoryInterface
     */
    private $processedFileRepository;

    /**
     * @var ProcessedFileLogRepositoryInterface
     */
    private $processedFileLogRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->productRepository          = $this->app->make(ProductRepositoryInterface::class);
        $this->categoryRepository         = $this->app->make(CategoryRepositoryInterface::class);
        $this->processedFileRepository    = $this->app->make(ProcessedFileRepositoryInterface::class);
        $this->processedFileLogRepository = $this->app->make(ProcessedFileLogRepositoryInterface::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_import_products(): void
    {
        $processedFile = ProcessedFileHelper::createProcessedFile(
            'products.xlsx',
            'tests/Stubs/ImportedSpreadsheet/Product/',
            'imported-spreadsheets'
        );

        ProductImportJob::dispatchNow($processedFile);

        $this->assertEquals($this->categoryRepository->count(), 1);
        $this->assertEquals($this->productRepository->count(), 1);

        $this->assertEquals(
            $this->processedFileRepository->find($processedFile->id)->status->value,
            ProcessedFileStatusEnum::Completed->value
        );

        Storage::disk('local')->assertMissing($processedFile->stored_filename);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_create_processed_file_log(): void
    {
        $processedFile = ProcessedFileHelper::createProcessedFile(
            'products_validations.xlsx',
            'tests/Stubs/ImportedSpreadsheet/Product/',
            'imported-spreadsheets'
        );

        ProductImportJob::dispatchNow($processedFile);

        $this->assertEquals($this->categoryRepository->count(), 0);
        $this->assertEquals($this->productRepository->count(), 0);
        $this->assertEquals($this->processedFileLogRepository->count(), 1);

        $this->assertEquals(
            $this->processedFileRepository->find($processedFile->id)->status->value,
            ProcessedFileStatusEnum::Failed->value
        );

        Storage::disk('local')->assertMissing($processedFile->stored_filename);
    }
}
