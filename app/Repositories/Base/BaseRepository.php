<?php

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * @return Model|ModelNotFoundException
     */
    public function findOrFail(int $id): Model|ModelNotFoundException
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }
}
