<?php

namespace App\Services\ProcessedFile;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ProcessedFile\ProcessedFileResource;
use App\Services\ProcessedFile\Contracts\ProcessedFileServiceInterface;
use App\Repositories\ProcessedFile\Contracts\ProcessedFileRepositoryInterface;
use App\Constants\HttpStatusConstant;

class ProcessedFileService implements ProcessedFileServiceInterface
{
    /**
     * @var ProcessedFileRepositoryInterface
     */
    private $processedFileRepository;

    /**
     * @param ProcessedFileRepositoryInterface $processedFileRepository
     *
     * @return void
     */
    public function __construct(ProcessedFileRepositoryInterface $processedFileRepository)
    {
        $this->processedFileRepository = $processedFileRepository;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getProcessedFile(int $id): array
    {
        try {
            $processedFile = $this->processedFileRepository->findOrFail($id);
            $processedFile = new ProcessedFileResource($processedFile);

            return [
                'code' => HttpStatusConstant::OK,
                'data' => $processedFile,
            ];
        } catch (Exception $e) {
            switch (get_class($e)) {
                case ModelNotFoundException::class:
                    return [
                        'code'    => HttpStatusConstant::NOT_FOUND,
                        'message' => trans('messages.not_found', ['attribute' => 'File processed']),
                    ];
                default:
                    return [
                        'code'    => HttpStatusConstant::INTERNAL_SERVER_ERROR,
                        'message' => trans('messages.internal_server_error'),
                    ];
            }
        }
    }
}
