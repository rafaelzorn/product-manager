<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use App\Services\Product\ProductService;
use App\Services\Product\Contracts\ProductServiceInterface;
use App\Services\ProcessSpreadsheet\ProcessSpreadsheetService;
use App\Services\ProcessSpreadsheet\Contracts\ProcessSpreadsheetServiceInterface;
use App\Services\ProcessedFile\ProcessedFileService;
use App\Services\ProcessedFile\Contracts\ProcessedFileServiceInterface;

class ServiceServiceProvider extends BaseServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(
            ProcessSpreadsheetServiceInterface::class,
            ProcessSpreadsheetService::class
        );
        $this->app->bind(
            ProcessedFileServiceInterface::class,
            ProcessedFileService::class
        );
    }
}
