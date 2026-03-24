<?php

namespace App\Modules\Wage\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WageUpdateRequest extends FormRequest
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
            "position" => "required|string|max:255",
            "unit" => "required|string",
            "price" => "required|numeric|min:0",
        ];
    }
}
