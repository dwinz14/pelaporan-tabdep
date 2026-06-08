<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik'      => ['required', 'string', 'regex:/^AP\d{9}$/', 'unique:users,nik'],
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['nullable', 'email', 'max:100'],
            'id_cabang' => ['required', 'exists:cabangs,id'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required'          => 'NIK wajib diisi.',
            'nik.regex'             => 'Format NIK tidak valid. Contoh: AP123456789',
            'nik.unique'            => 'NIK sudah terdaftar. Gunakan NIK lain atau hubungi Super Admin.',
            'name.required'         => 'Nama lengkap wajib diisi.',
            'id_cabang.required'    => 'Cabang wajib dipilih.',
            'id_cabang.exists'      => 'Cabang tidak ditemukan.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ];
    }
}
