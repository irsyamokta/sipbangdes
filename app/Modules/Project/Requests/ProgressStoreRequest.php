<?php

namespace App\Modules\Project\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgressStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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
