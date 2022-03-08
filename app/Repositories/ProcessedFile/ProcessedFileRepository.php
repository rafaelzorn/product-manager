<?php

namespace App\Repositories\ProcessedFile;

use App\Repositories\Base\BaseRepository;
use App\Repositories\ProcessedFile\Contracts\ProcessedFileRepositoryInterface;
use App\Models\ProcessedFile;

class ProcessedFileRepository extends BaseRepository implements ProcessedFileRepositoryInterface
{
    /**
     * @param ProcessedFile $processedFile
     *
     * @return void
     */
    public function __construct(ProcessedFile $processedFile)
    {
        $this->model = $processedFile;
    }
}
