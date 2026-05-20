<?php

namespace App\Http\Requests\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StorePeriodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_akhir' => [
                'required',
                'date',
                'unique:periode_laporans,tanggal_akhir',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->dayOfWeek !== Carbon::FRIDAY) {
                        $fail('Tanggal periode harus hari Jumat.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_akhir.required' => 'Tanggal periode wajib diisi.',
            'tanggal_akhir.date'     => 'Format tanggal tidak valid.',
            'tanggal_akhir.unique'   => 'Periode dengan tanggal ini sudah ada.',
        ];
    }
}
