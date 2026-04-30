<?php

namespace App\Modules\Project\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectExpenditureUpdateRequest extends FormRequest
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
            'description' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'date' => 'required|date',
            'information' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'Uraian wajib diisi.',
            'description.string' => 'Uraian harus berupa string.',
            'description.max' => 'Uraian maksimal 255 karakter.',
            'nominal.required' => 'Nominal wajib diisi.',
            'nominal.numeric' => 'Nominal harus berupa angka.',
            'nominal.min' => 'Nominal minimal 0.',
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Tanggal harus berupa tanggal.',
            'information.required' => 'Keterangan wajib diisi.',
            'information.string' => 'Keterangan harus berupa string.',
            'information.max' => 'Keterangan maksimal 255 karakter.',
        ];
    }
}
