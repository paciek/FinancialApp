<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'transaction_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:income,expense'],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
                    ->where(fn ($query) => $query
                        ->where('user_id', (int) $this->user()?->id)
                        ->where('type', (string) $this->input('type'))
                    ),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

