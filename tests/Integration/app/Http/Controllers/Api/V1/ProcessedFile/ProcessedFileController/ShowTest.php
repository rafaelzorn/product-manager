<?php

namespace Tests\Integration\app\Http\Controllers\Api\V1\ProcessedFile\ProcessedFileController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ProcessedFile;
use App\Http\Resources\ProcessedFile\ProcessedFileResource;
use App\Constants\HttpStatusConstant;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/api/v1/processed-files/';

    /**
     * @test
     *
     * @return void
     */
    public function should_return_processed_file(): void
    {
        // Arrange
        $data         = ProcessedFile::factory()->create();
        $dataResource = new ProcessedFileResource($data);

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
    public function should_return_processed_file_not_found(): void
    {
        // Arrange
        $nonExistingProcessedFileId = rand(1000, 3000);

        // Act
        $response = $this->getJson(self::ENDPOINT . $nonExistingProcessedFileId);

        // Assert
        $response->assertStatus(HttpStatusConstant::NOT_FOUND);
        $response->assertExactJson([
            'code'    => HttpStatusConstant::NOT_FOUND,
            'message' => trans('messages.not_found', ['attribute' => 'File processed']),
        ]);
    }
}
