<?php

namespace App\Modules\Project\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgressUpdateRequest extends FormRequest
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
            'description' => 'required|string',
            'documents'   => 'nullable|array',
            'documents.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'Keterangan wajib diisi.',
            'description.string' => 'Keterangan harus berupa string.',
            'description.max' => 'Keterangan maksimal 255 karakter.',

            'documents.array' => 'Format dokumen tidak valid.',
            'documents.*.image' => 'Dokumen harus berupa gambar.',
            'documents.*.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'documents.*.max' => 'Ukuran masing-masing gambar maksimal 2MB.',
        ];
    }
}
