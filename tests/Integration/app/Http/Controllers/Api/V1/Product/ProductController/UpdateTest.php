<?php

namespace Tests\Integration\app\Http\Controllers\Api\V1\Product\ProductController;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\ProductHelper;
use App\Models\Product;
use App\Http\Resources\Product\ProductResource;
use App\Constants\HttpStatusConstant;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/api/v1/products/';

    /**
     * @test
     *
     * @return void
     */
    public function should_update_product(): void
    {
        // Arrange
        $data         = Product::factory()->forCategory()->create();
        $dataToUpdate = ProductHelper::productFaker();

        $dataResource      = $dataToUpdate;
        $dataResource->id  = $data->id;
        $dataResource      = new ProductResource($dataResource);

        // Act
        $response = $this->putJson(self::ENDPOINT . $data->id, $dataToUpdate->getAttributes());

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
    public function should_return_validation_fields_required(): void
    {
        // Arrange
        $productId = rand(1000, 3000);

        $validations = [
            'category_id'   => trans('validation.required'),
            'name'          => trans('validation.required'),
            'free_shipping' => trans('validation.required'),
            'description'   => trans('validation.required'),
            'price'         => trans('validation.required'),
        ];

        // Act
        $response = $this->putJson(self::ENDPOINT . $productId);

        // Assert
        $response->assertStatus(HttpStatusConstant::UNPROCESSABLE_ENTITY);
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_validation_of_fields_types(): void
    {
        // Arrange
        $data = Product::factory()->forCategory()->create();

        $dataToUpdate                = ProductHelper::productFaker();
        $dataToUpdate->category_id   = Str::random(4);
        $dataToUpdate->name          = rand(1000, 3000);
        $dataToUpdate->free_shipping = Str::random(4);
        $dataToUpdate->description   = rand(1000, 3000);

        $validations = [
            'category_id'   => trans('validation.integer'),
            'name'          => trans('validation.string'),
            'free_shipping' => trans('validation.boolean'),
            'description'   => trans('validation.string'),
        ];

        // Act
        $response = $this->putJson(self::ENDPOINT . $data->id, $dataToUpdate->getAttributes());

        // Assert
        $response->assertStatus(HttpStatusConstant::UNPROCESSABLE_ENTITY);
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_validation_of_minimum_characters_fields(): void
    {
        // Arrange
        $data = Product::factory()->forCategory()->create();

        $dataToUpdate              = ProductHelper::productFaker();
        $dataToUpdate->name        = Str::random(1);
        $dataToUpdate->description = Str::random(1);

        $validations = [
            'name'        => trans('validation.min.string', ['min' => 3, 'attribute' => 'name']),
            'description' => trans('validation.min.string', ['min' => 3, 'attribute' => 'description']),
        ];

        // Act
        $response = $this->putJson(self::ENDPOINT . $data->id, $dataToUpdate->getAttributes());

        // Assert
        $response->assertStatus(HttpStatusConstant::UNPROCESSABLE_ENTITY);
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_validation_of_maximum_characters_fields(): void
    {
        // Arrange
        $data = Product::factory()->forCategory()->create();

        $dataToUpdate       = ProductHelper::productFaker();
        $dataToUpdate->name = Str::random(256);

        $validations = [
            'name' => trans('validation.max.string', ['max' => 255, 'attribute' => 'name']),
        ];

        // Act
        $response = $this->putJson(self::ENDPOINT . $data->id, $dataToUpdate->getAttributes());

        // Assert
        $response->assertStatus(HttpStatusConstant::UNPROCESSABLE_ENTITY);
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_validation_category_does_not_exist(): void
    {
        // Arrange
        $data = Product::factory()->forCategory()->create();

        $dataToUpdate              = ProductHelper::productFaker();
        $dataToUpdate->category_id = rand(1000, 3000);

        $validations = [
            'category_id' => trans('validation.exists'),
        ];

        // Act
        $response = $this->putJson(self::ENDPOINT . $data->id, $dataToUpdate->getAttributes());

        // Assert
        $response->assertStatus(HttpStatusConstant::UNPROCESSABLE_ENTITY);
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }

    // TODO Rafael Zorn 04/03/22: Test regex price

    /**
     * @test
     *
     * @return void
     */
    public function should_return_product_not_found(): void
    {
        // Arrange
        $nonExistingProductId = rand(1000, 3000);
        $dataToUpdate         = ProductHelper::productFaker()->getAttributes();

        // Act
        $response = $this->putJson(self::ENDPOINT . $nonExistingProductId, $dataToUpdate);

        // Assert
        $response->assertStatus(HttpStatusConstant::NOT_FOUND);
        $response->assertExactJson([
            'code'    => HttpStatusConstant::NOT_FOUND,
            'message' => trans('messages.not_found', ['attribute' => 'Product']),
        ]);
    }
}
