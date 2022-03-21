<?php

namespace Tests\Unit\app\Imports\Product;

use Exception;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Validators\ValidationException as MaatwebsiteValidationException;
use Maatwebsite\Excel\Excel;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;
use App\Repositories\Category\Contracts\CategoryRepositoryInterface;
use App\Repositories\ProcessedFile\Contracts\ProcessedFileRepositoryInterface;
use App\Imports\Product\ProductImport;
use App\Enums\ProcessedFileStatusEnum;
use App\Models\ProcessedFile;
use Tests\Helpers\ProcessedFileHelper;

class ProductImportTest extends TestCase
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
     * @var ProductImport
     */
    private $productImport;

    /**
     * @var Excel
     */
    private $excel;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->excel                   = $this->app->make(Excel::class);
        $this->productImport           = $this->app->make(ProductImport::class);
        $this->processedFileRepository = $this->app->make(ProcessedFileRepositoryInterface::class);
        $this->productRepository       = $this->app->make(ProductRepositoryInterface::class);
        $this->categoryRepository      = $this->app->make(CategoryRepositoryInterface::class);
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

        $this->import($processedFile);

        $this->assertEquals($this->categoryRepository->count(), 1);
        $this->assertEquals($this->productRepository->count(), 1);

        $this->assertEquals(
            $this->categoryRepository->find(123123)->toArray(),
            ['id' => 123123, 'name' => 'Ferramentas']
        );

        $this->assertEquals(
            $this->productRepository->find(1001)->toArray(),
            [
                'id'            => 1001,
                'category_id'   => 123123,
                'name'          => 'Furadeira X',
                'free_shipping' => false,
                'description'   => 'Furadeira eficiente X',
                'price'         => '100.01',
            ]
        );

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
    public function should_not_duplicate_category_and_product()
    {
        $category = Category::factory()->id(123123)->create();
        $product  = Product::factory()
                        ->categoryId($category->id)
                        ->id(1001)
                        ->create();

        $processedFile = ProcessedFileHelper::createProcessedFile(
            'products.xlsx',
            'tests/Stubs/ImportedSpreadsheet/Product/',
            'imported-spreadsheets'
        );

        $this->import($processedFile);

        $this->assertEquals($this->categoryRepository->count(), 1);
        $this->assertEquals($this->productRepository->count(), 1);

        $this->assertEquals(
            $this->categoryRepository->find($category->id)->toArray(),
            $category->toArray()
        );

        $this->assertEquals(
            $this->productRepository->find($product->id)->toArray(),
            $product->toArray()
        );

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
    public function should_throw_exception_of_required_fields_of_the_category(): void
    {
        try {
            $processedFile = ProcessedFileHelper::createProcessedFile(
                'category_required_fields.xlsx',
                'tests/Stubs/ImportedSpreadsheet/Product/',
                'imported-spreadsheets'
            );

            $this->import($processedFile);
        } catch (Exception $e) {
            $this->assertEquals(get_class($e), ValidationException::class);
            $this->assertEquals(
                $e->getMessage(),
                'The category id field is required. (and 1 more error)'
            );

            $this->assertEquals(
                $this->processedFileRepository->find($processedFile->id)->status->value,
                ProcessedFileStatusEnum::Processing->value
            );

            $this->assertEquals($this->categoryRepository->count(), 0);
            $this->assertEquals($this->productRepository->count(), 0);
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_throw_exception_of_types_fields_of_the_category(): void
    {
        try {
            $processedFile = ProcessedFileHelper::createProcessedFile(
                'category_fields_types.xlsx',
                'tests/Stubs/ImportedSpreadsheet/Product/',
                'imported-spreadsheets'
            );

            $this->import($processedFile);
        } catch (Exception $e) {
            $this->assertEquals(get_class($e), ValidationException::class);
            $this->assertEquals(
                $e->getMessage(),
                'The category id must be an integer. (and 1 more error)'
            );

            $this->assertEquals(
                $this->processedFileRepository->find($processedFile->id)->status->value,
                ProcessedFileStatusEnum::Processing->value
            );

            $this->assertEquals($this->categoryRepository->count(), 0);
            $this->assertEquals($this->productRepository->count(), 0);
        }
    }

     /**
     * @test
     *
     * @return void
     */
    public function should_throw_exception_of_the_minimum_amount_of_characters_of_the_category_name()
    {
        try {
            $processedFile = ProcessedFileHelper::createProcessedFile(
                'category_name_minimum_caracteres.xlsx',
                'tests/Stubs/ImportedSpreadsheet/Product/',
                'imported-spreadsheets'
            );

            $this->import($processedFile);
        } catch (Exception $e) {
            $this->assertEquals(get_class($e), ValidationException::class);
            $this->assertEquals(
                $e->getMessage(),
                'The category name must be at least 3 characters.'
            );

            $this->assertEquals(
                $this->processedFileRepository->find($processedFile->id)->status->value,
                ProcessedFileStatusEnum::Processing->value
            );

            $this->assertEquals($this->categoryRepository->count(), 0);
            $this->assertEquals($this->productRepository->count(), 0);
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_throw_exception_of_the_maximum_amount_of_characters_of_the_category_name()
    {
        try {
            $processedFile = ProcessedFileHelper::createProcessedFile(
                'category_name_maximum_caracteres.xlsx',
                'tests/Stubs/ImportedSpreadsheet/Product/',
                'imported-spreadsheets'
            );

            $this->import($processedFile);
        } catch (Exception $e) {
            $this->assertEquals(get_class($e), ValidationException::class);
            $this->assertEquals(
                $e->getMessage(),
                'The category name must not be greater than 255 characters.'
            );

            $this->assertEquals(
                $this->processedFileRepository->find($processedFile->id)->status->value,
                ProcessedFileStatusEnum::Processing->value
            );

            $this->assertEquals($this->categoryRepository->count(), 0);
            $this->assertEquals($this->productRepository->count(), 0);
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_throw_exception_of_product_field_validation()
    {
        try {
            $processedFile = ProcessedFileHelper::createProcessedFile(
                'products_validations.xlsx',
                'tests/Stubs/ImportedSpreadsheet/Product/',
                'imported-spreadsheets'
            );

            $this->import($processedFile);
        } catch (Exception $e) {
            $this->assertEquals(get_class($e), MaatwebsiteValidationException::class);
            $this->assertEquals(
                $e->getMessage(),
                'The 4.lm must be an integer. (and 12 more errors)'
            );

            $this->assertEquals(
                $this->processedFileRepository->find($processedFile->id)->status->value,
                ProcessedFileStatusEnum::Processing->value
            );

            $this->assertEquals($this->categoryRepository->count(), 1);
            $this->assertEquals($this->productRepository->count(), 0);
        }
    }

    /**
     * @param ProcessedFile $processedFile
     *
     * @return void
     */
    private function import(ProcessedFile $processedFile): void
    {
        $this->productImport->setProcessedFile($processedFile);

        $this->excel->import($this->productImport, $processedFile->stored_filename);
    }
}
