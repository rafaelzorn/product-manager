<?php

namespace Tests\Integration\app\Http\Controllers\Api\V1\Product\ProductController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Constants\HttpStatusConstant;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/api/v1/products/';

    /**
     * @test
     *
     * @return void
     */
    public function should_delete_product(): void
    {
        // Arrange
        $data = Product::factory()->forCategory()->create();

        // Act
        $response = $this->deleteJson(self::ENDPOINT . $data->id);

        // Assert
        $response->assertStatus(HttpStatusConstant::OK);
        $response->assertExactJson([
            'code'    => HttpStatusConstant::OK,
            'message' => trans('messages.record_removed', ['attribute' => 'Product']),
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
        $response = $this->deleteJson(self::ENDPOINT . $nonExistingProductId);

        // Assert
        $response->assertStatus(HttpStatusConstant::NOT_FOUND);
        $response->assertExactJson([
            'code'    => HttpStatusConstant::NOT_FOUND,
            'message' => trans('messages.not_found', ['attribute' => 'Product']),
        ]);
    }
}
