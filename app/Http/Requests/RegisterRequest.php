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

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login.required' => 'Pole login jest wymagane.',
            'login.string' => 'Login musi byc tekstem.',
            'login.min' => 'Login musi miec co najmniej :min znaki.',
            'login.max' => 'Login nie moze miec wiecej niz :max znakow.',
            'login.unique' => 'Podany login jest juz zajety.',
            'login.alpha_dash' => 'Login moze zawierac tylko litery, cyfry, myslnik i podkreslenie.',

            'email.required' => 'Pole email jest wymagane.',
            'email.email' => 'Podaj poprawny adres email.',
            'email.max' => 'Email nie moze miec wiecej niz :max znakow.',
            'email.unique' => 'Podany adres email jest juz zajety.',

            'email_confirmation.required' => 'Potwierdzenie email jest wymagane.',
            'email_confirmation.same' => 'Adresy email musza byc identyczne.',

            'password.required' => 'Pole haslo jest wymagane.',
            'password.min' => 'Haslo musi miec co najmniej :min znakow.',
            'password.confirmed' => 'Hasla musza byc identyczne.',

            'password_confirmation.required' => 'Potwierdzenie hasla jest wymagane.',

            'terms.required' => 'Akceptacja regulaminu jest wymagana.',
            'terms.accepted' => 'Akceptacja regulaminu jest wymagana.',
        ];
    }
}
