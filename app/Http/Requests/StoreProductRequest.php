<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;   // we’ll protect by permission middleware
    }

    public function rules(): array
    {
        return [
            'sku'                => 'required|string|unique:products,sku',
            'name'               => 'required|string|max:255',
            'category'           => 'required|string|max:120',
            'subcategory'        => 'nullable|string|max:120',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'tax_rate'           => 'required|numeric|min:0',
            'unit'               => 'required|string|max:50',
            'packaging'          => 'nullable|string|max:50',
            'min_order_quantity' => 'required|integer|min:1',
            'reorder_level'      => 'required|integer|min:1',
        ];
    }
}
