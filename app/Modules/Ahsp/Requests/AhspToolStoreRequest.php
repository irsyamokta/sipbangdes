<?php

namespace App\Modules\Ahsp\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AhspToolStoreRequest extends FormRequest
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
            'ahsp_id' => 'required|string',
            'tool_id' => 'required|string',
            'coefficient' => 'required|numeric|min:0',
        ];
    }
}
