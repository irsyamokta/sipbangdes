<?php

namespace App\Modules\Unit\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:10',
            'category' => 'required|string',
        ];
    }
}
