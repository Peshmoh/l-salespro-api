<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // `route('id')` picks the {id} from your URL
        $id = $this->route('id');

        return [
            'name'            => 'sometimes|required|string|max:255',
            'type'            => 'sometimes|required|string|max:100',
            'category'        => 'sometimes|required|in:A,A+,B,C',
            'contact_person'  => 'sometimes|required|string|max:255',
            'phone'           => 'sometimes|required|string|max:20',
            'email'           => 'nullable|email|unique:customers,email,' . $id,
            'tax_id'          => 'nullable|string|max:50',
            'payment_terms'   => 'sometimes|required|integer|min:0',
            'credit_limit'    => 'sometimes|required|numeric|min:0',
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
