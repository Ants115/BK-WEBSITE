<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Konsultasi;
use App\Models\JadwalKonseling; // Pastikan Model Jadwal Benar
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
        if ($siswa->biodataSiswa && ($siswa->biodataSiswa->status === 'Lulus' || $siswa->biodataSiswa->tahun_lulus != null)) {
            return redirect()->route('dashboard')
                ->with('error', 'Maaf, Alumni tidak dapat mengajukan konseling baru.');
        }
        // --- PAGAR ALUMNI SELESAI ---

        $guruBk = User::where('role', 'guru_bk')->get();

        // Mengambil jadwal guru untuk sidebar kanan
        $jadwalList = JadwalKonseling::with('guru')->orderBy('hari')->get(); 

        return view('konsultasi.create', compact('guruBk', 'jadwalList'));
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
        
        // 1. VALIDASI DATA
        $request->validate([
            'guru_id' => 'required|exists:users,id',
            'jadwal_pengajuan' => 'required|date', // Input dari datetime-local
            'topik' => 'required|string|min:3',
            'keluhan' => 'required|string', // Keluhan wajib diisi untuk detail
        ]);

        // 2. FORMAT JADWAL
        // Mengubah string datetime-local HTML menjadi format database
        $jadwalDiminta = Carbon::parse($request->jadwal_pengajuan);

        // 3. GABUNGKAN TOPIK & KELUHAN
        // Simpan 'Keluhan' ke dalam kolom 'topik' agar tersimpan di database
        $topikLengkap = $request->topik . " - (Detail: " . $request->keluhan . ")";

        // 4. SIMPAN KE DATABASE
        Konsultasi::create([
            'siswa_id' => Auth::id(),
            'guru_id' => $request->guru_id,
            'jadwal_diminta' => $jadwalDiminta,
            'topik' => $topikLengkap,
            'status' => 'Menunggu Persetujuan',
        ]);

        return redirect()->route('konsultasi.riwayat')->with('success', 'Permintaan konsultasi berhasil dikirim!');
    }

    /**
     * Menampilkan riwayat konsultasi siswa.
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
     * Menampilkan tiket detail konsultasi.
     */
    public function show($id)
    {
        $konsultasi = Konsultasi::where('siswa_id', Auth::id())->findOrFail($id);
        return view('konsultasi.tiket', compact('konsultasi'));
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

        return view('admin.konsultasi.index', compact('permintaan'));
    }

    /**
     * LAPORAN KONSULTASI (ADMIN / GURU BK)
     */
    public function laporan(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'guru_bk') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $guruList = User::where('role', 'guru_bk')->orderBy('name')->get();

        $statusOptions = [
            'Menunggu Persetujuan' => 'Menunggu Persetujuan',
            'Disetujui'            => 'Disetujui',
            'Ditolak'              => 'Ditolak',
            'Dijadwalkan Ulang'    => 'Dijadwalkan Ulang',
            'Selesai'              => 'Selesai',
        ];

        $guruId         = $request->query('guru_id');
        $status         = $request->query('status');
        $tanggalMulai   = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');
        $search         = $request->query('q');

        $query = Konsultasi::with(['siswa', 'guru']);

        if ($guruId) {
            $query->where('guru_id', $guruId);
        }

        if ($status && array_key_exists($status, $statusOptions)) {
            $query->where('status', $status);
        }

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

        $baseQuery = clone $query;
        $konsultasi = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $rekapStatus = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $rekapGuru = (clone $baseQuery)
            ->selectRaw('guru_id, COUNT(*) as total')
            ->groupBy('guru_id')
            ->get();

        $totalSemua = (clone $baseQuery)->count();
        $guruMap = $guruList->keyBy('id');

        return view('admin.konsultasi.laporan', compact(
            'konsultasi', 'guruList', 'guruMap', 'statusOptions',
            'rekapStatus', 'rekapGuru', 'totalSemua',
            'guruId', 'status', 'tanggalMulai', 'tanggalSelesai', 'search'
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
        if ($konsultasi->siswa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($konsultasi->status !== 'Menunggu Persetujuan') {
            return back()->with('error', 'Konsultasi yang sudah diproses tidak dapat dibatalkan.');
        }

        $konsultasi->delete();

        return back()->with('success', 'Permintaan konsultasi berhasil dibatalkan.');
    }

    public function cetakTiket(Konsultasi $konsultasi)
    {
        if ($konsultasi->siswa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($konsultasi->status !== 'Disetujui') {
            return back()->with('error', 'Tiket hanya bisa dicetak jika status Disetujui.');
        }

        $pdf = Pdf::loadView('konsultasi.tiket', compact('konsultasi'));
        
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

        $konsultasi->siswa->notify(new StatusKonsultasiNotification($konsultasi, 'Sesi konsultasi telah selesai. Lihat hasilnya.'));

        return back()->with('success', 'Konsultasi ditandai selesai.');
    }
}