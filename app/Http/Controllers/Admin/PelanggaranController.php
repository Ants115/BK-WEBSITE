<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // PENTING: Untuk akses tabel pivot/riwayat
use App\Models\User; 

class PelanggaranController extends Controller
{
    /**
     * Tampilkan Daftar Master Pelanggaran.
     */
   public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Pelanggaran::query();

        if ($search) {
            $query->where('nama_pelanggaran', 'like', "%{$search}%");
        }

        // PERBAIKAN: Ubah nama variabel jadi jamak ($pelanggarans)
        $pelanggarans = $query->paginate(10);

        // Kirim ke view dengan nama 'pelanggarans'
        return view('admin.pelanggaran.index', compact('pelanggarans', 'search'));
    }

    /**
     * Form Tambah Master Pelanggaran.
     */
    public function create()
    {
        return view('admin.pelanggaran.create');
    }

    /**
     * Simpan Master Pelanggaran Baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255',
            'poin' => 'required|integer|min:1',
            'kategori' => 'required|string',
        ]);

        Pelanggaran::create($request->all());

        return redirect()->route('admin.pelanggaran.index')->with('success', 'Data pelanggaran berhasil ditambahkan.');
    }

    /**
     * Form Edit Master Pelanggaran.
     */
    public function edit(Pelanggaran $pelanggaran)
    {
        return view('admin.pelanggaran.edit', compact('pelanggaran'));
    }

    /**
     * Update Master Pelanggaran.
     */
    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255',
            'poin' => 'required|integer|min:1',
            'kategori' => 'required|string',
        ]);

        $pelanggaran->update($request->all());

        return redirect()->route('admin.pelanggaran.index')->with('success', 'Data pelanggaran berhasil diupdate.');
    }

    /**
     * Hapus Master Pelanggaran.
     */
    public function destroy(Pelanggaran $pelanggaran)
    {
        $pelanggaran->delete();
        return redirect()->route('admin.pelanggaran.index')->with('success', 'Data pelanggaran berhasil dihapus.');
    }

    // =================================================================
    //  FUNGSI YANG HILANG (PENYEBAB ERROR)
    // =================================================================

    /**
     * Mencatat Pelanggaran ke Siswa (Tombol Merah di Profil Siswa)
     */
    public function storeSiswaPelanggaran(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'siswa_id' => 'required|exists:users,id', // <--- PERBAIKAN: Cek ke tabel 'users'
            'pelanggaran_id' => 'required|exists:pelanggarans,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // 2. Ambil data User (Siswa) dan Jenis Pelanggaran
        $siswa = User::findOrFail($request->siswa_id); // <--- PERBAIKAN: Pakai User
        $pelanggaran = Pelanggaran::findOrFail($request->pelanggaran_id);

        // 3. Simpan ke Tabel Riwayat
        DB::table('catatan_pelanggaran')->insert([
            'siswa_id' => $siswa->id,
            'pelanggaran_id' => $pelanggaran->id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'poin_saat_itu' => $pelanggaran->poin,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Update Total Poin (Logika Poin)
        // Kita simpan poin di biodata_siswa agar tabel users tetap bersih
        // Pastikan relasi biodataSiswa ada
        if ($siswa->biodataSiswa) {
            $siswa->biodataSiswa->poin_pelanggaran += $pelanggaran->poin;
            $siswa->biodataSiswa->save();
        } else {
            // Jika belum punya biodata, abaikan dulu atau buat baru (opsional)
        }

        return redirect()->back()->with('success', 'Pelanggaran berhasil dicatat.');
    }

    /**
     * Menghapus Riwayat Pelanggaran Siswa (Rollback Poin)
     */
   /**
     * Menghapus Riwayat Pelanggaran Siswa (Rollback Poin)
     */
    public function destroySiswaPelanggaran($id)
    {
        // 1. Cari data di tabel riwayat (tabel pivot)
        $riwayat = DB::table('catatan_pelanggaran')->where('id', $id)->first();

        if (!$riwayat) {
            return redirect()->back()->with('error', 'Data riwayat tidak ditemukan.');
        }

        // 2. Ambil data User (Siswa) - Ganti Siswa::find menjadi User::find
        $siswa = \App\Models\User::find($riwayat->siswa_id);
        
        if ($siswa && $siswa->biodataSiswa) {
            // Ambil poin yang pernah dicatat (snapshot)
            $poinDikurangi = $riwayat->poin_saat_itu ?? 0;
            
            // Kurangi poin di tabel biodata_siswa
            $siswa->biodataSiswa->poin_pelanggaran -= $poinDikurangi;
            
            // Pastikan poin tidak minus
            if ($siswa->biodataSiswa->poin_pelanggaran < 0) {
                $siswa->biodataSiswa->poin_pelanggaran = 0;
            }
            
            $siswa->biodataSiswa->save();
        }

        // 3. Hapus data dari tabel riwayat
        DB::table('catatan_pelanggaran')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Riwayat pelanggaran berhasil dihapus dan poin telah dikembalikan.');
    }
}