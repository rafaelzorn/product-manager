<?php

namespace App\Repositories\Category;

use App\Repositories\Base\BaseRepository;
use App\Repositories\Category\Contracts\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * @param Category $category
     *
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->model = $category;
    }
}
