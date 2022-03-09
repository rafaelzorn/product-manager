<?php

namespace App\Http\Controllers\Api\V1\ProcessedFile;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\Controller;
use App\Services\ProcessedFile\Contracts\ProcessedFileServiceInterface;

class ProcessedFileController extends Controller
{
    /**
     * @var ProcessedFileServiceInterface
     */
    private $processedFileService;

    /**
     * @param ProcessedFileServiceInterface $processedFileService
     *
     * @return void
     */
    public function __construct(ProcessedFileServiceInterface $processedFileService)
    {
        $this->processedFileService = $processedFileService;
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $response = $this->processedFileService->getProcessedFile($id);

        return $this->responseAdapter($response);
    }
}
