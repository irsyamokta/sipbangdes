<?php

namespace App\Modules\Tool\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToolStoreRequest extends FormRequest
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
