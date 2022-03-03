<?php

namespace App\Services\Product\Contracts;

interface ProductServiceInterface
{
    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    public function update(int $id, array $data): array;
}
