<?php

namespace Tests\Integration\app\Http\Controllers\Api\V1\Product\ProductController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Product;
use App\Http\Resources\Product\ProductResource;
use App\Constants\HttpStatusConstant;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/api/v1/products';

    /**
     * @test
     *
     * @return void
     */
    public function should_return_all_products(): void
    {
        // Arrange
        $count = 10;
        $data  = Product::factory()
                    ->forCategory()
                    ->count($count)
                    ->create();

        $data = ProductResource::collection($data)->resolve();

        // Act
        $response = $this->getJson(self::ENDPOINT);

        // Assert
        $response->assertStatus(HttpStatusConstant::OK);
        $response->assertJsonCount($count, 'data');
        $response->assertExactJson([
            'code' => HttpStatusConstant::OK,
            'data' => $data,
        ]);
    }
}
