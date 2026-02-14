<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "required|string|max:100",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8",
            "role" => "required|string",
            "is_active" => "required|boolean",
        ];
    }
}
