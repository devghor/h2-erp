<?php

namespace App\Http\Requests\Configuration\DeskGroup;

use App\Enums\Configuration\Desk\DeskGroupEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreDeskGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'type'        => ['nullable', new Enum(DeskGroupEnum::class)],
        ];
    }
}
