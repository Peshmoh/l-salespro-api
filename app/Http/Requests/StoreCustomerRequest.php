<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Adjust if you introduce policies/roles
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'type'            => 'required|string|max:100',
            'category'        => 'required|in:A,A+,B,C',
            'contact_person'  => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'email'           => 'nullable|email|unique:customers,email',
            'tax_id'          => 'nullable|string|max:50',
            'payment_terms'   => 'required|integer|min:0',
            'credit_limit'    => 'required|numeric|min:0',
            'current_balance' => 'nullable|numeric|min:0',
            'latitude'        => 'nullable|numeric|between:-90,90',
            'longitude'       => 'nullable|numeric|between:-180,180',
            'address'         => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'category.in' => 'Category must be one of: A, A+, B, C.',
        ];
    }
}
