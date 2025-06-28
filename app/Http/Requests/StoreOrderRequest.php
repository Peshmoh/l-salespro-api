<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * We already protect the route with Sanctum + middleware,
     * so simply return true here.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating an order.
     */
    public function rules(): array
    {
        return [
            'customer_id'               => 'required|exists:customers,id',
            'discount'                  => 'nullable|numeric|min:0',

            'items'                     => 'required|array|min:1',
            'items.*.product_id'        => 'required|exists:products,id',
            'items.*.warehouse_id'      => 'required|exists:warehouses,id',
            'items.*.quantity'          => 'required|integer|min:1',
        ];
    }

    /**
     * Optional: custom validation messages.
     */
    public function messages(): array
    {
        return [
            'customer_id.required'          => 'Customer is required.',
            'customer_id.exists'            => 'Customer not found.',
            'items.required'                => 'Please add at least one order item.',
            'items.*.product_id.required'   => 'Product is required for each item.',
            'items.*.product_id.exists'     => 'Product not found.',
            'items.*.warehouse_id.required' => 'Warehouse is required for each item.',
            'items.*.warehouse_id.exists'   => 'Warehouse not found.',
            'items.*.quantity.required'     => 'Quantity is required for each item.',
            'items.*.quantity.min'          => 'Quantity must be at least 1.',
        ];
    }
}
