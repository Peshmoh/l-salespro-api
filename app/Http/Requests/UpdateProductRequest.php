<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku'                => ['sometimes','string',Rule::unique('products','sku')->ignore($this->route('id'))],
            'name'               => 'sometimes|string|max:255',
            'category'           => 'sometimes|string|max:120',
            'subcategory'        => 'sometimes|nullable|string|max:120',
            'description'        => 'sometimes|nullable|string',
            'price'              => 'sometimes|numeric|min:0',
            'tax_rate'           => 'sometimes|numeric|min:0',
            'unit'               => 'sometimes|string|max:50',
            'packaging'          => 'sometimes|nullable|string|max:50',
            'min_order_quantity' => 'sometimes|integer|min:1',
            'reorder_level'      => 'sometimes|integer|min:1',
        ];
    }
}
