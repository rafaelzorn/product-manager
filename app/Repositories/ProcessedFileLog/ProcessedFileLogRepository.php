<?php

namespace App\Repositories\ProcessedFileLog;

use App\Repositories\Base\BaseRepository;
use App\Repositories\ProcessedFileLog\Contracts\ProcessedFileLogRepositoryInterface;
use App\Models\ProcessedFileLog;

class ProcessedFileLogRepository extends BaseRepository implements ProcessedFileLogRepositoryInterface
{
    /**
     * @param ProcessedFileLog $processedFileLog
     *
     * @return void
     */
    public function __construct(ProcessedFileLog $processedFileLog)
    {
        $this->model = $processedFileLog;
    }
}
