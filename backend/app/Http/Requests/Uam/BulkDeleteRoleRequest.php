<?php

namespace App\Http\Requests\Uam;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:roles,id'],
        ];
    }
}
