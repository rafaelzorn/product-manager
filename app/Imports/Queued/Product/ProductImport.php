<?php

namespace App\Imports\Queued\Product;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Enums\ProcessedFileStatusEnum;
use App\Models\ProcessedFile;
use App\Models\Category;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;
use App\Repositories\Category\Contracts\CategoryRepositoryInterface;
use App\Repositories\ProcessedFileLog\Contracts\ProcessedFileLogRepositoryInterface;
use App\Imports\Queued\ImportQueuedInterface;

class ProductImport implements ImportQueuedInterface, ToCollection, WithHeadingRow, WithEvents, WithValidation
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProcessedFileLogRepositoryInterface
     */
    private $processedFileLogRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var Category
     */
    private $category;

    /**
     * @var ProcessedFile
     */
    private $processedFile;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ProcessedFileLogRepositoryInterface $processedFileLogRepository
     *
     * @return void
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        ProcessedFileLogRepositoryInterface $processedFileLogRepository
    )
    {
        $this->productRepository          = $productRepository;
        $this->categoryRepository         = $categoryRepository;
        $this->processedFileLogRepository = $processedFileLogRepository;
    }

    /**
     * @param ProcessedFile $processedFile
     *
     * @return void
     */
    public function setProcessedFile($processedFile): void
    {
        $this->processedFile = $processedFile;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                $this->processedFile->update([
                    'status' => ProcessedFileStatusEnum::Processing->value
                ]);

                $this->updateOrCreateCategory($event);
            },

            AfterImport::class => function(AfterImport $event) {
                $this->processedFile->update([
                    'status' => ProcessedFileStatusEnum::Completed->value
                ]);
            },

            ImportFailed::class => function(ImportFailed $event) {
                $this->processedFile->update([
                    'status' => ProcessedFileStatusEnum::Failed->value
                ]);

                $this->saveLogException($event->getException());
            },
        ];
    }

    /**
     * @param BeforeImport $event
     *
     * @return void
     */
    private function updateOrCreateCategory(BeforeImport $event): void
    {
        $activeSheet = $event->reader->getActiveSheet();
        $category    = [
            'category_id'   => $activeSheet->getCell('B1')->getValue(),
            'category_name' => $activeSheet->getCell('C4')->getValue(),
        ];

        $this->validateCategory($category);

        $this->category = $this->categoryRepository->updateOrCreate(
            ['id'   => $category['category_id']],
            ['name' => $category['category_name']]
        );
    }

    /**
     * @param array $category
     *
     * @return void
     */
    private function validateCategory(array $category): void
    {
        Validator::make($category, [
            'category_id'   => 'required|integer',
            'category_name' => 'required|string|min:3|max:255',
        ])->validate();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'lm'            => 'required|integer',
            'name'          => 'required|string|min:3|max:255',
            'free_shipping' => 'required|boolean',
            'description'   => 'required|string|min:3',
            'price'         => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }

    /**
     * @return int
     */
    public function headingRow(): int
    {
        return 3;
    }

    /**
     * @param Collection $rows
     *
     * @return void
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $this->updateOrCreateProduct($row);
        }
    }

    /**
     * @param mixed $row
     *
     * @return void
     */
    private function updateOrCreateProduct(mixed $row): void
    {
        $product = [
            'id'            => $row['lm'],
            'category_id'   => $this->category->id,
            'name'          => $row['name'],
            'free_shipping' => $row['free_shipping'],
            'description'   => $row['description'],
            'price'         => $row['price'],
        ];

        $this->productRepository->updateOrCreate(['id' => $product['id']], $product);
    }

    /**
     * @param Exception $exception
     *
     * @return void
     */
    private function saveLogException(Exception $exception): void
    {
        $this->processedFileLogRepository->create([
            'processed_file_id' => $this->processedFile->id,
            'exception_message' => $exception->getMessage(),
            'exception_trace'   => $exception->getTraceAsString(),
        ]);
    }
}
