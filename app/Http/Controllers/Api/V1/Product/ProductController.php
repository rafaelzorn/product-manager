<?php

namespace App\Http\Controllers\Api\V1\Product;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\Controller;
use App\Services\Product\Contracts\ProductServiceInterface;
use App\Http\Requests\Product\ProductUpdateRequest;

class ProductController extends Controller
{
    /**
     * @var ProductServiceInterface
     */
    private $productService;

    /**
     * @param ProductServiceInterface $productService
     *
     * @return void
     */
    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @param ProductUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, int $id): JsonResponse
    {
        $response = $this->productService->update($id, $request->all());

        return $this->responseAdapter($response);
    }
}
