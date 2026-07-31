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
            'position_ids'         => ['required_if:type,Position', 'prohibited_unless:type,Position', 'array'],
            'position_ids.*'       => ['integer', 'exists:positions,id'],
            'position_group_ids'   => ['required_if:type,PositionGroup', 'prohibited_unless:type,PositionGroup', 'array'],
            'position_group_ids.*' => ['integer', 'exists:position_groups,id'],
            'hrbp_ids'             => ['required_if:type,HRBP', 'prohibited_unless:type,HRBP', 'array'],
            'hrbp_ids.*'           => ['integer', 'exists:hrbps,id'],
        ];
    }
}
