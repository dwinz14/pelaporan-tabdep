<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik'      => [
                'required',
                'string',
                'regex:/^AP\d{9}$/',
                Rule::unique('users', 'nik')->ignore($this->route('user')),
            ],
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['nullable', 'email', 'max:100'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role'     => ['required', Rule::enum(UserRole::class)],
            'id_cabang' => [
                Rule::requiredIf(fn() => $this->input('role') === UserRole::PicCabang->value),
                'nullable',
                'exists:cabangs,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required'          => 'NIK wajib diisi.',
            'nik.regex'             => 'Format NIK tidak valid. Contoh: AP123456789',
            'nik.unique'            => 'NIK sudah digunakan user lain.',
            'name.required'         => 'Nama lengkap wajib diisi.',
            'password.min'          => 'Password minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'role.required'         => 'Role wajib dipilih.',
            'id_cabang.required_if' => 'Cabang wajib dipilih untuk role PIC Cabang.',
        ];
    }
}
