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

    /**
     * @param array $attributes
     * @param array $values
     *
     * @return Model
     */
    public function firstOrCreate(array $attributes, array $values = []): Model;

    /**
     * @param array $attributes
     *
     * @return bool
     */
    public function update(array $attributes): bool;

    /**
     * @return Model
     */
    public function first(): Model;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param int $id
     *
     * @return Model
     */
    public function find(int $id): Model;
}
