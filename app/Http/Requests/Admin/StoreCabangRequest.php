<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCabangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_cabang' => ['required', 'string', 'max:10', 'unique:cabangs,kode_cabang'],
            'nama_cabang' => ['required', 'string', 'max:100'],
            'alamat'      => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_cabang.required' => 'Kode cabang wajib diisi.',
            'kode_cabang.max'      => 'Kode cabang maksimal 10 karakter.',
            'kode_cabang.unique'   => 'Kode cabang sudah terdaftar.',
            'nama_cabang.required' => 'Nama cabang wajib diisi.',
            'nama_cabang.max'      => 'Nama cabang maksimal 100 karakter.',
        ];
    }
}
