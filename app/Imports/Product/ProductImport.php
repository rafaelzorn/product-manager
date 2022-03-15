<?php

namespace App\Imports\Product;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use App\Models\Category;
use App\Imports\Base\BaseImport;
use App\Enums\ProcessedFileStatusEnum;
use App\Repositories\Category\Contracts\CategoryRepositoryInterface;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;

class ProductImport extends BaseImport
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var Category
     */
    private $category;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     *
     * @return void
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository
    )
    {
        $this->productRepository  = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                $this->updateStatusFileProcessed(ProcessedFileStatusEnum::Processing);
                $this->updateOrCreateCategory($event);
            },

            AfterImport::class => function(AfterImport $event) {
                $this->afterImport();
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
     * @return int
     */
    public function headingRow(): int
    {
        return 3;
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
}
