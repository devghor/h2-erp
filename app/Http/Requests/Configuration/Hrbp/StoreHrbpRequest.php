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
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
