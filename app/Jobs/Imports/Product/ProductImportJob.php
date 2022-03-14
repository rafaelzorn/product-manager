<?php

namespace App\Jobs\Imports\Product;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ProcessedFile;

class ProductImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ProcessedFile
     */
    private $processedFile;

    /**
     * @param ProcessedFile $processedFile
     *
     * @return void
     */
    public function __construct(ProcessedFile $processedFile)
    {
        $this->processedFile = $processedFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        dd($this->processedFile);
    }
}
