<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Base\Contracts\BaseRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\Contracts\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;
use App\Repositories\ProcessedFile\ProcessedFileRepository;
use App\Repositories\ProcessedFile\Contracts\ProcessedFileRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProcessedFileRepositoryInterface::class, ProcessedFileRepository::class);
    }
}
