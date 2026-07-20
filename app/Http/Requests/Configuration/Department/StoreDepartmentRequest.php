<?php

namespace App\Http\Requests\Configuration\Department;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'division_id' => 'nullable|integer|exists:divisions,id',
            'description' => 'nullable|string',
            'department_head_user_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
