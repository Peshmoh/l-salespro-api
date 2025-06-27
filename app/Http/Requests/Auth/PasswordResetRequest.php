<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token'    => ['required'],
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => [
                'required', 'confirmed', 'min:8',
                'regex:/[A-Z]/',       // At least one uppercase letter
                'regex:/[0-9]/',       // At least one number
                'regex:/[@$!%*?&]/',   // At least one special character
            ],
        ];
    }
}
