<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class IndonesianPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! preg_match('/^(?:\+?62|0)8[1-9][0-9]{6,10}$/', $value)) {
            $fail('The :attribute must be a valid Indonesian phone number.');
        }
    }

    /**
     * Normalize an Indonesian phone number to the 62xxxxxxxxxx format.
     */
    public static function normalize(string $value): string
    {
        $digits = preg_replace('/\D/', '', $value) ?? $value;

        return match (true) {
            str_starts_with($digits, '62') => $digits,
            str_starts_with($digits, '0') => '62'.substr($digits, 1),
            default => $digits,
        };
    }
}
