<?php

namespace App\Http\Requests\Configuration\ApprovalLevel;

use App\Enums\Configuration\ApprovalLevelTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreApprovalLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'type'           => ['required', new Enum(ApprovalLevelTypeEnum::class)],
            'position_ids'   => ['required_if:type,Position', 'prohibited_unless:type,Position', 'array'],
            'position_ids.*' => ['integer', 'exists:positions,id'],
            'hrbp_id'        => ['required_if:type,HRBP', 'prohibited_unless:type,HRBP', 'nullable', 'integer', 'exists:hrbps,id'],
        ];
    }
}
