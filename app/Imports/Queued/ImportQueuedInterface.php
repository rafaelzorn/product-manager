<?php

namespace App\Imports\Queued;

use Illuminate\Support\Collection;

interface ImportQueuedInterface
{
    /**
     * @param Collection $rows
     *
     * @return void
     */
    public function collection(Collection $rows): void;

    /**
     * @return void
     */
    public function chunkSize(): int;
}
