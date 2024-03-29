<?php

namespace Tests\Integration\app\Http\Controllers\Api\V1\Product\ProductController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Http\Resources\Product\ProductResource;
use App\Constants\HttpStatusConstant;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/api/v1/products/';

    /**
     * @test
     *
     * @return void
     */
    public function should_return_product(): void
    {
        // Arrange
        $data         = Product::factory()->forCategory()->create();
        $dataResource = new ProductResource($data);

        // Act
        $response = $this->getJson(self::ENDPOINT . $data->id);

        // Assert
        $response->assertStatus(HttpStatusConstant::OK);
        $response->assertExactJson([
            'code' => HttpStatusConstant::OK,
            'data' => $dataResource->resolve(),
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_product_not_found(): void
    {
        // Arrange
        $nonExistingProductId = rand(1000, 3000);

        // Act
        $response = $this->getJson(self::ENDPOINT . $nonExistingProductId);

        // Assert
        $response->assertStatus(HttpStatusConstant::NOT_FOUND);
        $response->assertExactJson([
            'code'    => HttpStatusConstant::NOT_FOUND,
            'message' => trans('messages.not_found', ['attribute' => 'Product']),
        ]);
    }
}
