<?php

namespace App\Modules\Rab\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RabCommentStoreRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'action' => 'required|in:send,forward,revision,approve',
            'comment' => 'nullable|string|max:1000',
        ];
    }
}
