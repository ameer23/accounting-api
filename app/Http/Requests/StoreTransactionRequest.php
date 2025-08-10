<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reference' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'entries' => ['required', 'array', 'size:2'],
            'entries.*.account_id' => ['required', 'exists:accounts,id'],
            'entries.*.type' => ['required', 'in:debit,credit'],
            'entries.*.amount' => ['required', 'numeric', 'gt:0'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $entries = $validator->safe()->entries;

                if (empty($entries) || count($entries) !== 2) {
                    return;
                }

                $entry1 = $entries[0];
                $entry2 = $entries[1];

                if ($entry1['amount'] !== $entry2['amount']) {
                    $validator->errors()->add(
                        'entries',
                        'The debit and credit amounts must be equal.'
                    );
                }

                if ($entry1['type'] === $entry2['type']) {
                    $validator->errors()->add(
                        'entries',
                        'A transaction must have one debit and one credit entry.'
                    );
                }
            }
        ];
    }
}