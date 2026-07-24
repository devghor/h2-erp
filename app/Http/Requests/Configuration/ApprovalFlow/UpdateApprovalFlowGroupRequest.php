<?php

namespace App\Http\Requests\Configuration\ApprovalFlow;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApprovalFlowGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'position_group_id'         => ['required', 'integer', 'exists:position_groups,id'],
            'items'                     => ['required', 'array', 'min:1'],
            'items.*.approval_level_id' => ['required', 'integer', 'exists:approval_levels,id'],
            'items.*.sequence'          => ['required', 'integer', 'min:1'],
        ];
    }
}
