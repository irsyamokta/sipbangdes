<?php

namespace App\Modules\WorkerCategory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkerItemStoreRequest extends FormRequest
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
            'category_id' => 'required|string',
            'ahsp_id' => 'required|string',
            'unit' => 'required|string',
        ];
    }
}
