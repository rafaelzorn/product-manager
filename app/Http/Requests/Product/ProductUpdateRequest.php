<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Base\BaseFormRequest;

class ProductUpdateRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'category_id'   => 'required|integer|exists:categories,id',
            'name'          => 'required|string|min:3|max:255',
            'free_shipping' => 'required|boolean',
            'description'   => 'required|string|min:3',
            'price'         => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }
}
