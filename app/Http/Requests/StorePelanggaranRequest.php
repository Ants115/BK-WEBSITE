<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePelanggaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Set ke true agar semua user yang terautentikasi bisa membuat request ini.
        // Nanti bisa ditambahkan logika hak akses di sini.
        return true;
    }

    public function rules(): array
    {
        // Pindahkan aturan validasi Anda ke sini
        return [
            'nama_pelanggaran' => 'required|string|max:255',
            'poin' => 'required|integer|min:1',
        ];
    }
}