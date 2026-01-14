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
        // --- PAGAR ALUMNI MULAI ---
        $siswa = Auth::user();
        
        // Cek apakah data biodata ada DAN statusnya 'Lulus' (atau tahun_lulus terisi)
        if ($siswa->biodataSiswa && ($siswa->biodataSiswa->status === 'Lulus' || $siswa->biodataSiswa->tahun_lulus != null)) {
            return redirect()->route('dashboard')
                ->with('error', 'Maaf, Alumni tidak dapat mengajukan konseling baru.');
        }
        // --- PAGAR ALUMNI SELESAI ---

        // ... sisa kode create yang lama ...
        $guruBk = User::where('role', 'guru_bk')->get();
        return view('siswa.konsultasi.create', compact('guruBk'));
    }
    /**
     * Menyimpan permintaan janji temu baru dari siswa.
     */
    public function store(Request $request)
    {
        // --- PAGAR ALUMNI MULAI ---
        $siswa = Auth::user();
        if ($siswa->biodataSiswa && ($siswa->biodataSiswa->status === 'Lulus' || $siswa->biodataSiswa->tahun_lulus != null)) {
             return redirect()->route('dashboard');
        }
        // --- PAGAR ALUMNI SELESAI ---
        
        $request->validate([
            'guru_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'topik' => 'required|string|min:5',
        ]);

        $jadwalDiminta = Carbon::parse($request->tanggal . ' ' . $request->jam)->toDateTimeString();

        Konsultasi::create([
            'siswa_id' => Auth::id(),
            'guru_id' => $request->guru_id,
            'jadwal_diminta' => $jadwalDiminta,
            'topik' => $request->topik,
        ]);

        return redirect()->route('konsultasi.riwayat')->with('success', 'Permintaan konsultasi berhasil dikirim!');
    }

    /**
     * Menampilkan riwayat konsultasi siswa.
     * Kode ini mengambil SEMUA status (Menunggu, Disetujui, Ditolak, dll).
     */
    public function riwayat()
    {
        $riwayat = Konsultasi::where('siswa_id', Auth::id())
                            ->with('guru')
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return view('konsultasi.riwayat', compact('riwayat'));
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

        // Optional: properti "jadwal_aktif" bisa di-handle di accessor model,
        // untuk sekarang kita pakai apa adanya di view.
        return view('admin.konsultasi.index', compact('permintaan'));
    }

    /**
     * LAPORAN KONSULTASI (ADMIN / GURU BK)
     */
    public function laporan(Request $request)
    {
        // Pastikan hanya guru BK yang boleh akses
        if (!Auth::check() || Auth::user()->role !== 'guru_bk') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Data untuk filter
        $guruList = User::where('role', 'guru_bk')->orderBy('name')->get();

        $statusOptions = [
            'Menunggu Persetujuan' => 'Menunggu Persetujuan',
            'Disetujui'            => 'Disetujui',
            'Ditolak'              => 'Ditolak',
            'Dijadwalkan Ulang'    => 'Dijadwalkan Ulang',
            'Selesai'              => 'Selesai',
        ];

        // Ambil nilai filter dari query string
        $guruId         = $request->query('guru_id');
        $status         = $request->query('status');
        $tanggalMulai   = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');
        $search         = $request->query('q');

        // Query dasar
        $query = Konsultasi::with(['siswa', 'guru']);

        if ($guruId) {
            $query->where('guru_id', $guruId);
        }

        if ($status && array_key_exists($status, $statusOptions)) {
            $query->where('status', $status);
        }

        // Untuk laporan, kita filter berdasarkan tanggal dibuat (created_at).
        // Kalau kamu nanti mau ganti ke jadwal_disetujui juga bisa.
        if ($tanggalMulai) {
            $query->whereDate('created_at', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai) {
            $query->whereDate('created_at', '<=', $tanggalSelesai);
        }

        if ($search) {
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Simpan base query untuk rekap
        $baseQuery = clone $query;

        // Data utama tabel (paginate)
        $konsultasi = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Rekap per status
        $rekapStatus = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Rekap per guru
        $rekapGuru = (clone $baseQuery)
            ->selectRaw('guru_id, COUNT(*) as total')
            ->groupBy('guru_id')
            ->get();

        // Total semua
        $totalSemua = (clone $baseQuery)->count();

        // Map guru id => object agar tidak query berulang di view
        $guruMap = $guruList->keyBy('id');

        return view('admin.konsultasi.laporan', compact(
            'konsultasi',
            'guruList',
            'guruMap',
            'statusOptions',
            'rekapStatus',
            'rekapGuru',
            'totalSemua',
            'guruId',
            'status',
            'tanggalMulai',
            'tanggalSelesai',
            'search'
        ));
    }

    /**
     * Menyetujui permintaan konsultasi.
     */
    public function setujui(Konsultasi $konsultasi)
    {
        $konsultasi->update([
            'status' => 'Disetujui',
            'jadwal_disetujui' => $konsultasi->jadwal_diminta 
        ]);

        // Kirim Notifikasi
        $konsultasi->siswa->notify(new StatusKonsultasiNotification($konsultasi, 'Permintaan konsultasi Anda telah disetujui.'));

        return back()->with('success', 'Permintaan konsultasi telah disetujui.');
    }

    /**
     * Menolak permintaan konsultasi.
     */
    public function tolak(Request $request, Konsultasi $konsultasi)
    {
        $request->validate(['pesan_guru' => 'required|string|min:5']);

        $konsultasi->update([
            'status' => 'Ditolak',
            'pesan_guru' => $request->pesan_guru
        ]);

        // Kirim Notifikasi
        $konsultasi->siswa->notify(new StatusKonsultasiNotification($konsultasi, 'Permintaan konsultasi Anda ditolak.'));

        return back()->with('success', 'Permintaan konsultasi telah ditolak.');
    }

    /**
     * Menjadwalkan ulang permintaan konsultasi.
     */
    public function jadwalkanUlang(Request $request, Konsultasi $konsultasi)
    {
        $request->validate([
            'tanggal_baru' => 'required|date',
            'jam_baru' => 'required',
            'pesan_guru' => 'required|string|min:5',
        ]);

        $jadwalBaru = Carbon::parse($request->tanggal_baru . ' ' . $request->jam_baru)->toDateTimeString();

        $konsultasi->update([
            'status' => 'Dijadwalkan Ulang',
            'jadwal_disetujui' => $jadwalBaru,
            'pesan_guru' => $request->pesan_guru
        ]);

        // Kirim Notifikasi
        $konsultasi->siswa->notify(new StatusKonsultasiNotification($konsultasi, 'Jadwal konsultasi Anda diubah oleh Guru.'));

        return back()->with('success', 'Permintaan konsultasi telah dijadwalkan ulang.');
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca.
     */
    public function bacaNotifikasi($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->route('konsultasi.riwayat');
    }

    public function destroy(Konsultasi $konsultasi)
    {
        // Cek 1: Pastikan yang menghapus adalah pemilik data sendiri
        if ($konsultasi->siswa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek 2: Pastikan status masih 'Menunggu Persetujuan'
        if ($konsultasi->status !== 'Menunggu Persetujuan') {
            return back()->with('error', 'Konsultasi yang sudah diproses tidak dapat dibatalkan.');
        }

        // Hapus data
        $konsultasi->delete();

        return back()->with('success', 'Permintaan konsultasi berhasil dibatalkan.');
    }

    public function cetakTiket(Konsultasi $konsultasi)
    {
        // 1. Validasi Keamanan (Hanya pemilik data yang boleh cetak)
        if ($konsultasi->siswa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Pastikan statusnya sudah disetujui
        if ($konsultasi->status !== 'Disetujui') {
            return back()->with('error', 'Tiket hanya bisa dicetak jika status Disetujui.');
        }

        // 3. Load View PDF
        $pdf = Pdf::loadView('konsultasi.tiket', compact('konsultasi'));
        
        // 4. Download File
        return $pdf->download('Tiket-Konsultasi-'.$konsultasi->id.'.pdf');
    }

    public function selesaikan(Request $request, Konsultasi $konsultasi)
    {
        $request->validate([
            'hasil_konseling' => 'required|string|min:5',
        ]);

        $konsultasi->update([
            'status' => 'Selesai',
            'hasil_konseling' => $request->hasil_konseling
        ]);

        // Kirim notifikasi ke siswa (Opsional, tapi bagus)
        $konsultasi->siswa->notify(new StatusKonsultasiNotification($konsultasi, 'Sesi konsultasi telah selesai. Lihat hasilnya.'));

        return back()->with('success', 'Konsultasi ditandai selesai.');
    }
}
