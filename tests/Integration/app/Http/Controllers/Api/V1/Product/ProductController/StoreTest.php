<?php

namespace Tests\Integration\app\Http\Controllers\Api\V1\Product\ProductController;

use Tests\TestCase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\ProcessedFile\Contracts\ProcessedFileRepositoryInterface;
use App\Jobs\Imports\Product\ProductImportJob;
use App\Constants\HttpStatusConstant;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ProcessedFileRepositoryInterface
     */
    private $processedFileRepository;

    private const ENDPOINT = '/api/v1/products/';

    public function setUp(): void
    {
        parent::setUp();

        $this->processedFileRepository = $this->app->make(ProcessedFileRepositoryInterface::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_status_endpoint_of_file_in_process(): void
    {
        // Arrange
        Bus::fake();

        $file = UploadedFile::fake()->create('products.xlsx', 16);

        // Act
        $response = $this->postJson(self::ENDPOINT, ['spreadsheet' => $file]);

        $processedFileId = $this->processedFileRepository->first()->id;
        $endpoint        = route('processed-files.show', ['id' => $processedFileId]);

        $data = [
            'endpoint_spreadsheet_processing_status' => $endpoint,
        ];

        // Assert
        $response->assertStatus(HttpStatusConstant::OK);
        $response->assertExactJson([
            'code'    => HttpStatusConstant::OK,
            'data'    => $data,
            'message' => trans('messages.spreadsheet_sent_for_processing')
        ]);

        Bus::assertDispatched(ProductImportJob::class);
    }

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
