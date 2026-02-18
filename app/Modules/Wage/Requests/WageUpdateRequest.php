<?php

namespace App\Modules\Wage\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WageUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            "position" => "required|string|max:255",
            "unit" => "required|string",
            "price" => "required|numeric|min:0",
        ];
    }
}
