<?php

namespace App\Repositories\Product\Contracts;

use App\Repositories\Base\Contracts\BaseRepositoryInterface;
use App\Models\Product;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param int $id
     * @param array $data
     *
     * @return Product
     */
    public function updateProduct(int $id, array $data): Product;
}
