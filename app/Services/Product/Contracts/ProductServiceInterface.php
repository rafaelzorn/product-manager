<?php

namespace App\Services\Product\Contracts;

use Illuminate\Http\UploadedFile;

interface ProductServiceInterface
{
    /**
     * @return array
     */
    public function getAllProducts(): array;

    /**
     * @param UploadedFile $spreadsheet
     *
     * @return array
     */
    public function importProducts(UploadedFile $spreadsheet): array;

    /**
     * @param int $id
     *
     * @return array
     */
    public function getProduct(int $id): array;

    /**
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    public function updateProduct(int $id, array $data): array;

    /**
     * @param int $id
     *
     * @return array
     */
    public function deleteProduct(int $id): array;
}
