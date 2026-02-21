<?php

namespace App\Modules\Project\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgressStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'percentage' => 'required|numeric|min:0.1|max:100',
            'description' => 'required|string',

            'documents' => 'required|array',
            'documents.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }
}
