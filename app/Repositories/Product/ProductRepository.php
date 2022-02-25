<?php

namespace App\Repositories\Product;

use App\Repositories\Base\BaseRepository;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * @param Product $product
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }
}
