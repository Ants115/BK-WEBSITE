<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Tingkatan; 
use Illuminate\Validation\Rule;
use App\Models\Jurusan;


class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Gunakan 'with' untuk mengambil data relasi secara efisien (Eager Loading)
    $kelasList = Kelas::with(['tingkatan', 'jurusan'])->get();
    return view('admin.kelas.index', ['kelasList' => $kelasList]);
}

    /**
     * Show the form for creating a new resource.
     */

     public function create()
     {
         $tingkatanList = Tingkatan::all(); // <-- BARIS INI PENTING
         $jurusanList = Jurusan::all();
         return view('admin.kelas.create', [
             'tingkatanList' => $tingkatanList, // <-- KIRIM DATA TINGKATAN
             'jurusanList' => $jurusanList,
         ]);
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tingkatan_id' => 'required|exists:tingkatan,id', // <-- PASTIKAN INI ADA
            'jurusan_id' => 'required|exists:jurusan,id',
            'nama_unik' => [
                'required',
                'string',
                'max:10',
                // Memastikan kombinasi tingkatan, jurusan, dan nama_unik adalah unik
                Rule::unique('kelas')->where(function ($query) use ($request) { return $query->where('tingkatan_id', $request->tingkatan_id)
                                 ->where('jurusan_id', $request->jurusan_id); }),
            ],
            'tahun_ajaran' => 'required|string|max:10',
        ]);

        // Ambil data relasi untuk membuat nama kelas yang deskriptif
        $tingkatan = Tingkatan::find($validated['tingkatan_id']);
        $jurusan = Jurusan::find($validated['jurusan_id']);

        // Buat nama kelas lengkap menggunakan singkatan, contoh: "X RPL 1"
        $validated['nama_kelas'] = sprintf( 
            '%s %s %s',
            $tingkatan->nama_tingkatan,
            $jurusan->singkatan ?? $jurusan->nama_jurusan, // Prioritaskan singkatan
            $validated['nama_unik']
        );

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas baru berhasil ditambahkan.');
    }
    /**
     * Show the form for editing the specified resource.
     */
    
public function edit(Kelas $kela)
{
    $tingkatanList = Tingkatan::all(); // <-- BARIS INI PENTING
    $jurusanList = Jurusan::all();

    return view('admin.kelas.edit', [
        'kelas' => $kela,
        'tingkatanList' => $tingkatanList, // <-- KIRIM DATA TINGKATAN
        'jurusanList' => $jurusanList,
    ]);
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'tingkatan_id' => 'required|exists:tingkatan,id',
            'jurusan_id' => 'required|exists:jurusan,id',
            'nama_unik' => [
                'required',
                'string',
                'max:10',
                Rule::unique('kelas')->where(function ($query) use ($request) {
                    return $query->where('tingkatan_id', $request->tingkatan_id)
                                 ->where('jurusan_id', $request->jurusan_id);
                })->ignore($kelas->id), // Abaikan data saat ini saat validasi unik
            ],
            'tahun_ajaran' => 'required|string|max:10',
        ]);

        $tingkatan = Tingkatan::find($validated['tingkatan_id']);
        $jurusan = Jurusan::find($validated['jurusan_id']);

        // Buat nama kelas lengkap menggunakan singkatan, contoh: "X RPL 1"
        $validated['nama_kelas'] = sprintf( 
            '%s %s %s',
            $tingkatan->nama_tingkatan,
            $jurusan->singkatan ?? $jurusan->nama_jurusan, // Prioritaskan singkatan
            $validated['nama_unik']
        );

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
{
    $kelas->delete();
    return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil dihapus.');
}
}
