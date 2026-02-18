<?php

namespace App\Modules\Material\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            "name" => "required|string|max:255",
            "unit" => "required|string",
            "price" => "required|numeric|min:0",
        ];
    }
}
