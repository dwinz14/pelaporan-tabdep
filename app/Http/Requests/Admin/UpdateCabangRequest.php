<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCabangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_cabang' => [
                'required',
                'string',
                'max:10',
                Rule::unique('cabangs', 'kode_cabang')->ignore($this->route('cabang')),
            ],
            'nama_cabang' => ['required', 'string', 'max:100'],
            'alamat'      => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_cabang.required' => 'Kode cabang wajib diisi.',
            'kode_cabang.unique'   => 'Kode cabang sudah digunakan cabang lain.',
            'nama_cabang.required' => 'Nama cabang wajib diisi.',
        ];
    }
}
