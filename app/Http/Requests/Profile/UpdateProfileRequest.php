<?php

namespace App\Http\Requests\Profile;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:100'],
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore(auth()->id()),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max'      => 'Nama maksimal 100 karakter.',
            'email.email'   => 'Format email tidak valid.',
            'email.unique'  => 'Email sudah digunakan oleh akun lain.',
        ];
    }
}
