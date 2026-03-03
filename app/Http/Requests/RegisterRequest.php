<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'min:3', 'max:50', 'unique:users,login', 'alpha_dash'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'email_confirmation' => ['required', 'same:email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'terms' => ['required', 'accepted'],
        ];
    }
}
