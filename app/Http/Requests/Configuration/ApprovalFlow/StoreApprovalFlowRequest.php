<?php

namespace App\Http\Requests\Configuration\ApprovalFlow;

use App\Enums\Configuration\ApprovalFlowTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreApprovalFlowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', new Enum(ApprovalFlowTypeEnum::class)],
        ];
    }
}
