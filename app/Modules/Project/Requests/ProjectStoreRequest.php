<?php

namespace App\Modules\Project\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'project_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'chairman' => 'required|string|max:255',
            'budget_year' => 'required|numeric',
            'project_status' => 'required|string',
        ];
    }
}
