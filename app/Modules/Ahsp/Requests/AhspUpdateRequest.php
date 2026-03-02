<?php

namespace App\Modules\Ahsp\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AhspUpdateRequest extends FormRequest
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
            'work_name' => 'required|string|max:255',
            'unit' => 'required'
        ];
    }
}
