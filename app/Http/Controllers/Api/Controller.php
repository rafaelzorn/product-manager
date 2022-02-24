<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @param array $data
     *
     * @return JsonResponse
     */
    public function responseAdapter(array $data): JsonResponse
    {
        $response         = [];
        $response['code'] = $data['code'];

        if (isset($data['message'])) {
            $response['message'] = $data['message'];
        }

        if (isset($data['data'])) {
            $response['data'] = $data['data'];
        }

        return response()->json($response, $response['code']);
    }
}
