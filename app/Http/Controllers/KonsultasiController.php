<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\StatusKonsultasiNotification;

class KonsultasiController extends Controller
{
    //======================================================================
    // LOGIKA UNTUK SISWA
    //======================================================================

    /**
     * Menampilkan form untuk membuat janji temu.
     */
    public function create()
    {
        // Ambil semua guru BK
        $guruList = User::where('role', 'bk')->get();

        return view('konsultasi.create', compact('guruList'));
    }

    /**
     * Menyimpan permintaan janji temu baru dari siswa.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'guru_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam'     => 'required',
            'topik'   => 'required|string|min:5',
        ]);

        $jadwalDiminta = Carbon::parse($data['tanggal'] . ' ' . $data['jam'])->toDateTimeString();

        Konsultasi::create([
            'siswa_id'       => Auth::id(),
            'guru_id'        => $data['guru_id'],
            'jadwal_diminta' => $jadwalDiminta,
            'topik'          => $data['topik'],
            // pastikan status awal konsisten
            'status'         => Konsultasi::STATUS_MENUNGGU,
        ]);

        return redirect()
            ->route('konsultasi.riwayat')
            ->with('success', 'Permintaan konsultasi berhasil dikirim!');
    }

    /**
     * Menampilkan riwayat konsultasi siswa.
     * Mengambil SEMUA status (Menunggu, Disetujui, Ditolak, Dijadwalkan Ulang, Selesai).
     */
    public function riwayat()
    {
        $riwayat = Konsultasi::where('siswa_id', Auth::id())
            ->with('guru')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('konsultasi.riwayat', compact('riwayat'));
    }

    /**
     * Menandai notifikasi konsultasi sebagai sudah dibaca.
     */
    public function bacaNotifikasi($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->route('konsultasi.riwayat');
    }

    /**
     * Menghapus permintaan konsultasi oleh siswa.
     * Hanya boleh jika:
     * - pemiliknya adalah siswa yang login
     * - status masih Menunggu Persetujuan
     */
    public function destroy(Konsultasi $konsultasi)
    {
        // Pastikan yang menghapus adalah pemilik data sendiri
        if ($konsultasi->siswa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya boleh hapus jika status masih menunggu
        if ($konsultasi->status !== Konsultasi::STATUS_MENUNGGU) {
            return back()->with('error', 'Konsultasi yang sudah diproses tidak dapat dibatalkan.');
        }

        $konsultasi->delete();

        return back()->with('success', 'Permintaan konsultasi berhasil dibatalkan.');
    }

    /**
     * Cetak tiket konsultasi (PDF) untuk siswa.
     * Hanya bisa jika:
     * - pemilik data adalah siswa yang login
     * - status sudah Disetujui
     */
    public function cetakTiket(Konsultasi $konsultasi)
    {
        // Validasi pemilik data
        if ($konsultasi->siswa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan status Disetujui
        if ($konsultasi->status !== Konsultasi::STATUS_DISETUJUI) {
            return back()->with('error', 'Tiket hanya bisa dicetak jika status Disetujui.');
        }

        $pdf = Pdf::loadView('konsultasi.tiket', compact('konsultasi'));

        return $pdf->download('Tiket-Konsultasi-' . $konsultasi->id . '.pdf');
    }

    //======================================================================
    // LOGIKA UNTUK ADMIN / GURU BK
    //======================================================================

    /**
     * Menampilkan semua permintaan konsultasi di dashboard admin.
     */
    public function index()
    {
        $permintaan = Konsultasi::with(['siswa', 'guru'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Sesuaikan dengan nama folder view-mu:
        // jika foldernya "resources/views/Admin/konsultasi/index.blade.php"
        // maka gunakan "Admin.konsultasi.index"
        return view('Admin.konsultasi.index', compact('permintaan'));
    }

    /**
     * Menyetujui permintaan konsultasi.
     */
    public function setujui(Konsultasi $konsultasi)
    {
        $konsultasi->update([
            'status'           => Konsultasi::STATUS_DISETUJUI,
            'jadwal_disetujui' => $konsultasi->jadwal_disetujui ?: $konsultasi->jadwal_diminta,
        ]);

        // Kirim Notifikasi ke siswa
        $konsultasi->siswa->notify(
            new StatusKonsultasiNotification(
                $konsultasi,
                'Permintaan konsultasi Anda telah disetujui.'
            )
        );

        return back()->with('success', 'Permintaan konsultasi telah disetujui.');
    }

    /**
     * Menolak permintaan konsultasi.
     */
    public function tolak(Request $request, Konsultasi $konsultasi)
    {
        $data = $request->validate([
            'pesan_guru' => 'required|string|min:5',
        ]);

        $konsultasi->update([
            'status'     => Konsultasi::STATUS_DITOLAK,
            'pesan_guru' => $data['pesan_guru'],
        ]);

        // Kirim Notifikasi
        $konsultasi->siswa->notify(
            new StatusKonsultasiNotification(
                $konsultasi,
                'Permintaan konsultasi Anda ditolak.'
            )
        );

        return back()->with('success', 'Permintaan konsultasi telah ditolak.');
    }

    /**
     * Menjadwalkan ulang permintaan konsultasi.
     * Jadwal baru disimpan di kolom jadwal_disetujui.
     */
    public function jadwalkanUlang(Request $request, Konsultasi $konsultasi)
    {
        $data = $request->validate([
            'tanggal_baru' => 'required|date',
            'jam_baru'     => 'required',
            'pesan_guru'   => 'required|string|min:5',
        ]);

        $jadwalBaru = Carbon::parse($data['tanggal_baru'] . ' ' . $data['jam_baru'])->toDateTimeString();

        $konsultasi->update([
            'status'           => Konsultasi::STATUS_DIJADWALKAN_ULANG,
            'jadwal_disetujui' => $jadwalBaru,
            'pesan_guru'       => $data['pesan_guru'],
        ]);

        // Kirim Notifikasi
        $konsultasi->siswa->notify(
            new StatusKonsultasiNotification(
                $konsultasi,
                'Jadwal konsultasi Anda diubah oleh Guru.'
            )
        );

        return back()->with('success', 'Permintaan konsultasi telah dijadwalkan ulang.');
    }

    /**
     * Menandai sesi konsultasi sebagai selesai dan menyimpan hasil_konseling.
     */
    public function selesaikan(Request $request, Konsultasi $konsultasi)
    {
        $data = $request->validate([
            'hasil_konseling' => 'required|string|min:5',
        ]);

        $konsultasi->update([
            'status'          => Konsultasi::STATUS_SELESAI,
            'hasil_konseling' => $data['hasil_konseling'],
        ]);

        // Kirim notifikasi ke siswa (opsional tapi bagus)
        $konsultasi->siswa->notify(
            new StatusKonsultasiNotification(
                $konsultasi,
                'Sesi konsultasi telah selesai. Silakan lihat hasil konseling Anda.'
            )
        );

        return back()->with('success', 'Konsultasi ditandai selesai dan hasil konseling tersimpan.');
    }
}
