<?php

namespace App\Services\Product;

use App\Services\Product\Contracts\ProductServiceInterface;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;
use App\Http\Resources\Product\ProductResource;
use App\Constants\HttpStatusConstant;

class ProductService implements ProductServiceInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     *
     * @return void
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $data = $this->productRepository->get();
        $data = ProductResource::collection($data);

        return [
            'code' => HttpStatusConstant::OK,
            'data' => $data,
        ];
    }

    /**
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return ['code' => HttpStatusConstant::OK];
    }
}
