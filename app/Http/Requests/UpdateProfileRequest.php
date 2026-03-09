<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = (int) $this->user()->id;

        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[\pL\s\-\.]+$/u',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\pL\pN\s,\.\-\/]+$/u',
            ],
            'contact_phone' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^\+?[0-9\s\-\(\)]{7,20}$/',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'Nazwa moze zawierac tylko litery, spacje, myslnik i kropke.',
            'address.regex' => 'Adres ma nieprawidlowy format.',
            'contact_phone.regex' => 'Telefon kontaktowy ma nieprawidlowy format.',
        ];
    }
}
