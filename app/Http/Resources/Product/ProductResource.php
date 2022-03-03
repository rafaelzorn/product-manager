<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'category_name' => $this->category->name,
            'name'          => $this->name,
            'free_shipping' => $this->free_shipping,
            'description'   => $this->description,
            'price'         => $this->price,
        ];
    }
}
