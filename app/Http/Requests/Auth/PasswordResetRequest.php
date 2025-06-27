<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    public function authorize(): bool   { return true; }

    public function rules(): array
    {
        return [
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => [
                'required','confirmed','min:8',
                'regex:/[A-Z]/',        // ≥1 uppercase
                'regex:/[0-9]/',        // ≥1 number
                'regex:/[@$!%*?&]/',    // ≥1 special char
            ],
        ];
    }
}
