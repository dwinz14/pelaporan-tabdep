<?php

namespace App\Http\Requests\Akunting;

use Illuminate\Foundation\Http\FormRequest;

class RequestRevisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'catatan_revisi' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'catatan_revisi.required' => 'Catatan revisi wajib diisi.',
            'catatan_revisi.min'      => 'Catatan revisi minimal 10 karakter agar PIC memahami perbaikan yang diperlukan.',
            'catatan_revisi.max'      => 'Catatan revisi maksimal 1000 karakter.',
        ];
    }
}
