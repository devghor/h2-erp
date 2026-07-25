<?php

namespace App\Http\Requests\Configuration\Shift;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'working_days' => 'required|array|min:1',
            'working_days.*' => 'required|integer|between:0,6',
            'weekends' => 'required|array|min:1',
            'weekends.*' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'special_working_dates' => 'nullable|array',
            'special_working_dates.*' => 'required|date_format:Y-m-d',
            'special_weekend_dates' => 'nullable|array',
            'special_weekend_dates.*' => 'required|date_format:Y-m-d',
        ];
    }
}
