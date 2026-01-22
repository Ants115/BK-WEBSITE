<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. Cek apakah user mengirim token (apakah kotak dicentang?)
        if (empty($value)) {
            $fail('Mohon centang kotak "Saya bukan robot"!');
            return;
        }

        // 2. Cek ke Server Google: "Apakah token ini asli?"
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $value,
        ]);

        // 3. Jika Google bilang "false" (Palsu/Robot), gagalkan.
        if ($response->failed() || !$response->json('success')) {
            $fail('Verifikasi Captcha gagal. Silakan coba lagi.');
        }
    }
}