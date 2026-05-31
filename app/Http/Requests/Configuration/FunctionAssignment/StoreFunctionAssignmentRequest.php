<?php

namespace App\Http\Requests\Configuration\FunctionAssignment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFunctionAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = auth()->user()->company_id;

        return [
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:100',
            'user_ids'    => 'required|array|min:1',
            'user_ids.*'  => 'required|integer|exists:users,id',
            'description' => 'nullable|string',
            'type'        => [
                'required',
                'integer',
                Rule::unique('function_assignments', 'type')
                    ->where('company_id', $companyId),
            ],
        ];
    }
}
