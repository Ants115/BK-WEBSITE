<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;

class KonsultasiController extends Controller
{
    /**
     * Tampilkan semua permintaan konsultasi.
     */
    public function index()
    {
        $permintaan = Konsultasi::with(['siswa', 'guru'])
            ->orderByDesc('jadwal_diminta')
            ->get();

        return view('Admin.konsultasi.index', compact('permintaan'));
    }

    /**
     * Setujui permintaan konsultasi.
     * - Set status ke Disetujui
     * - Jika belum ada jadwal_disetujui, pakai jadwal_diminta.
     */
    public function setujui(Konsultasi $konsultasi)
    {
        $konsultasi->update([
            'status'           => Konsultasi::STATUS_DISETUJUI,
            'jadwal_disetujui' => $konsultasi->jadwal_disetujui ?: $konsultasi->jadwal_diminta,
        ]);

        return back()->with('success', 'Permintaan konsultasi telah disetujui.');
    }

    /**
     * Tolak permintaan konsultasi dengan alasan (pesan_guru).
     */
    public function tolak(Request $request, Konsultasi $konsultasi)
    {
        $data = $request->validate([
            'pesan_guru' => 'required|string|max:1000',
        ]);

        $konsultasi->update([
            'status'     => Konsultasi::STATUS_DITOLAK,
            'pesan_guru' => $data['pesan_guru'],
        ]);

        return back()->with('success', 'Permintaan konsultasi telah ditolak.');
    }

    /**
     * Jadwalkan ulang konsultasi (tanggal & jam baru + pesan_guru).
     * Jadwal baru disimpan di kolom jadwal_disetujui.
     */
    public function jadwalkanUlang(Request $request, Konsultasi $konsultasi)
    {
        $data = $request->validate([
            'tanggal_baru' => 'required|date',
            'jam_baru'     => 'required',
            'pesan_guru'   => 'required|string|max:1000',
        ]);

        $tanggalBaru = $data['tanggal_baru'] . ' ' . $data['jam_baru'];

        $konsultasi->update([
            'status'           => Konsultasi::STATUS_DISETUJUI,
            'jadwal_disetujui' => $tanggalBaru,
            'pesan_guru'       => $data['pesan_guru'],
        ]);

        return back()->with('success', 'Jadwal konsultasi berhasil dijadwalkan ulang.');
    }

    /**
     * Tandai sesi konsultasi sebagai selesai dan simpan hasil_konseling.
     */
    public function selesaikan(Request $request, Konsultasi $konsultasi)
    {
        $data = $request->validate([
            'hasil_konseling' => 'required|string',
        ]);

        $konsultasi->update([
            'status'          => Konsultasi::STATUS_SELESAI,
            'hasil_konseling' => $data['hasil_konseling'],
        ]);

        return back()->with('success', 'Sesi konsultasi telah diselesaikan dan hasil konseling tersimpan.');
    }
}
