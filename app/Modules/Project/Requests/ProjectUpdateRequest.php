<?php

namespace App\Modules\Project\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
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
            'project_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'chairman' => 'required|string|max:255',
            'budget_year' => 'required|numeric',
            'volume' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'project_status' => 'required|string',
        ];
    }
}
