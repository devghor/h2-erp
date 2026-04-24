<?php

namespace App\Http\Requests\Uam;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->has('permissions')) {
            return [
                'permissions' => ['required', 'array'],
                'permissions.*' => ['string', 'exists:permissions,name'],
            ];
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
