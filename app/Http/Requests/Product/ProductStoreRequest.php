<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Base\BaseFormRequest;

class ProductStoreRequest extends BaseFormRequest
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
            'spreadsheet' => 'required|mimes:xlsx',
        ];
    }
}
