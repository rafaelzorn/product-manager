<?php

namespace App\Repositories\Base\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * @return Collection
     */
    public function get(): Collection;

    /**
     * @param int $id
     *
     * @return Model
     */
    public function findOrFail(int $id): ?Model;
}
