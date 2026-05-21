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
        $rules = [
            'project_id' => 'required|exists:projects,id',
            'action' => 'required|in:send,forward,revision,approve',
            'comment' => 'nullable|string|max:1000',
        ];

        if (in_array($this->action, ['revision', 'forward'])) {
            $rules['comment'] = 'required|string|max:1000';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'comment.required' => 'Catatan/Komentar wajib diisi.',
            'comment.string' => 'Catatan/Komentar harus berupa string.',
            'comment.max' => 'Catatan/Komentar maksimal 1000 karakter.',
        ];
    }
}
