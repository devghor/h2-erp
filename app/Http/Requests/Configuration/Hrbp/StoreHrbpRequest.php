<?php

namespace App\Http\Requests\Configuration\Hrbp;

use Illuminate\Foundation\Http\FormRequest;

class StoreHrbpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'user_ids'   => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ];
    }
}
