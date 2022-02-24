<?php

namespace App\Http\Controllers\Api\V1\Product;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\Controller;

class ProductController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->responseAdapter(['code' => 200]);
    }

    /**
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        return $this->responseAdapter(['code' => 200]);
    }

    /**
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        return $this->responseAdapter(['code' => 200]);
    }

    /**
     * @return JsonResponse
     */
    public function update(): JsonResponse
    {
        return $this->responseAdapter(['code' => 200]);
    }

    /**
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        return $this->responseAdapter(['code' => 200]);
    }
}
