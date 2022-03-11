<?php

namespace App\Imports\Queued\Product;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Imports\Queued\ImportQueuedInterface;

class ProductImport implements ImportQueuedInterface, ToCollection, WithChunkReading
{
    /**
     * @param Collection $rows
     *
     * @return void
     */
    public function collection(Collection $rows): void
    {
        dd('IMPORT COLLECTION');
    }

    /**
     * @return void
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
