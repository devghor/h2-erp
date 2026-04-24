<?php

namespace App\Http\Requests\Uam;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ulids' => ['required', 'array'],
            'ulids.*' => ['string', 'exists:users,ulid'],
        ];
    }
}
