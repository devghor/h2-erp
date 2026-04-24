<?php

namespace App\Http\Requests\Uam;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->has('roles')) {
            return [
                'roles' => ['required', 'array'],
                'roles.*' => ['string', 'exists:roles,name'],
            ];
        }

        $userId = $this->route('user')?->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', "unique:users,email,{$userId}"],
            'username' => ['nullable', 'string', 'max:255', "unique:users,username,{$userId}"],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
