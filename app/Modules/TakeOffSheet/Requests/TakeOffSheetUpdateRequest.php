<?php

namespace App\Modules\TakeOffSheet\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TakeOffSheetUpdateRequest extends FormRequest
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
            'project_id' => 'required|exists:projects,id',
            'worker_category_id' => 'required|exists:worker_categories,id',
            'ahsp_id' => 'required|exists:ahsps,id',

            'work_name' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',

            'note' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'project_id.required' => 'Proyek wajib dipilih.',
            'project_id.exists' => 'Proyek yang dipilih tidak valid atau tidak ditemukan.',

            'worker_category_id.required' => 'Kategori pekerjaan wajib dipilih.',
            'worker_category_id.exists' => 'Kategori pekerjaan tidak valid atau tidak ditemukan.',

            'ahsp_id.required' => 'AHSP wajib dipilih.',
            'ahsp_id.exists' => 'AHSP yang dipilih tidak valid atau tidak ditemukan.',
        ];
    }
}
