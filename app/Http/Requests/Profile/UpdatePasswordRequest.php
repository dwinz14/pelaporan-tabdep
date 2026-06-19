<?php

namespace App\Http\Requests\Profile;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password'      => ['required', 'string', 'current_password'],
            'password'              => ['required', 'string', 'min:6', new StrongPassword, 'confirmed', 'different:current_password'],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required'         => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.min'                      => 'Password baru minimal 6 karakter.',
            'password.confirmed'                => 'Konfirmasi password tidak cocok.',
            'password.different'                => 'Password baru tidak boleh sama dengan password saat ini.',
            'password_confirmation.required'    => 'Konfirmasi password wajib diisi.',
        ];
    }
}
