<?php

namespace Tests\Helpers;

use Faker\Factory;
use App\Models\Category;
use App\Models\Product;

class ProductHelper
{
    /**
     * @return Product $product
     */
    public static function productFaker(): Product
    {
        $category = Category::factory()->create();
        $faker    = Factory::create();

        $product = new Product();

        $product->category_id   = $category->id;
        $product->name          = $faker->name();
        $product->free_shipping = $faker->boolean();
        $product->description   = $faker->sentence();
        $product->price         = $faker->randomFloat(2, 1, 100);

        return $product;
    }
}
