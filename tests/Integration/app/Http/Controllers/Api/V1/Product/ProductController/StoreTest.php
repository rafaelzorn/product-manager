<?php

namespace Tests\Integration\app\Http\Controllers\Api\V1\Product\ProductController;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Constants\HttpStatusConstant;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/api/v1/products/';

    /**
     * @test
     *
     * @return void
     */
    public function should_return_validation_fields_required(): void
    {
        // Arrange
        $validations = [
            'spreadsheet' => trans('validation.required'),
        ];

        // Act
        $response = $this->postJson(self::ENDPOINT);

        // Assert
        $response->assertStatus(HttpStatusConstant::UNPROCESSABLE_ENTITY);
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_validation_mimes(): void
    {
        // Arrange
        Storage::fake('imported-spreadsheets');

        $file = UploadedFile::fake()->create('products.xls', 16);

        $validations = [
            'spreadsheet' => trans('validation.mimes', ['values' => 'xlsx']),
        ];

        // Act
        $response = $this->postJson(self::ENDPOINT, ['spreadsheet' => $file]);

        // Assert
        $response->assertStatus(HttpStatusConstant::UNPROCESSABLE_ENTITY);
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }
}
