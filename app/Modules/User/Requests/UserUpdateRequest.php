<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "required|string|max:100",
            "email" => "required|email",
            "role" => "required|string",
            "password" => "nullable|min:8",
            "is_active" => "required|boolean",
        ];
    }
}
