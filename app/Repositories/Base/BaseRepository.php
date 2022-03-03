<?php

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Base\Contracts\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->model->get();
    }

    /**
     * @param int $id
     *
     * @return Model
     */
    public function findOrFail(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }
}
