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
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $response = $this->productService->getAllProducts();

        return $this->responseAdapter($response);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $response = $this->productService->getProduct($id);

        return $this->responseAdapter($response);
    }

    /**
     * @param ProductUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, int $id): JsonResponse
    {
        $response = $this->productService->updateProduct($id, $request->all());

        return $this->responseAdapter($response);
    }
}
