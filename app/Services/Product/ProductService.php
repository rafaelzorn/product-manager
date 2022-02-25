<?php

namespace App\Services\Product;

use App\Services\Product\Contracts\ProductServiceInterface;
use App\Constants\HttpStatusConstant;

class ProductService implements ProductServiceInterface
{
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
