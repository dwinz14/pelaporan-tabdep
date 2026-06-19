<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('Password tidak valid.');
            return;
        }

        $missing = [];

        if (! preg_match('/[A-Z]/', $value)) {
            $missing[] = 'huruf kapital (A-Z)';
        }

        if (! preg_match('/[0-9]/', $value)) {
            $missing[] = 'angka (0-9)';
        }

        if (! preg_match('/[^A-Za-z0-9]/', $value)) {
            $missing[] = 'karakter spesial (!@#$%, dll)';
        }

        if (! empty($missing)) {
            $fail('Password harus mengandung ' . implode(', ', $missing) . '.');
        }
    }
}
