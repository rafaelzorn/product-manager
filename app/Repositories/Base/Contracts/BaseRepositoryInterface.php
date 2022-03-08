<?php

namespace App\Repositories\Base\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface BaseRepositoryInterface
{
    /**
     * @return Collection
     */
    public function get(): Collection;

    /**
     * @param int $id
     *
     * @return Model|ModelNotFoundException
     */
    public function findOrFail(int $id): Model|ModelNotFoundException;

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model;
}
