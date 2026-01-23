<?php

namespace App\Http\Controllers;


use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // 1. Tampilkan Halaman Chat
    public function index(Request $request)
    {
        // Jika ADMIN/GURU BK: Tampilkan daftar siswa yang pernah chat
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'guru_bk') {
            // Ambil daftar user yang pernah kirim pesan ke Admin ATAU dikirimi pesan oleh Admin
            $users = User::where('role', 'siswa')
                ->whereHas('pesanTerkirim', function($q) {
                    $q->where('penerima_id', Auth::id());
                })
                ->orWhereHas('pesanDiterima', function($q) {
                    $q->where('pengirim_id', Auth::id());
                })
                ->get();
                
            return view('chat.index_admin', compact('users'));
        } 
        
        // Jika SISWA: Langsung buka chat room dengan Admin (ID 1 contohnya)
        // Asumsi: Admin BK selalu user dengan ID 1 (atau sesuaikan query cari admin)
        $admin = User::where('role', 'admin')->first(); 
        return redirect()->route('chat.show', $admin->id);
    }

    // 2. Buka Chat Room Spesifik
    public function show($id)
    {
        $lawanBicara = User::findOrFail($id);
        $saya = Auth::id();

        // Ambil semua chat antara SAYA dan DIA (urutkan dari lama ke baru)
        $chats = Pesan::where(function ($q) use ($saya, $id) {
            $q->where('pengirim_id', $saya)->where('penerima_id', $id);
        })->orWhere(function ($q) use ($saya, $id) {
            $q->where('pengirim_id', $id)->where('penerima_id', $saya);
        })->orderBy('created_at', 'asc')->get();

        return view('chat.show', compact('chats', 'lawanBicara'));
    }

    // 3. Kirim Pesan
    public function store(Request $request)
    {
        Pesan::create([
            'pengirim_id' => Auth::id(),
            'penerima_id' => $request->penerima_id,
            'isi' => $request->isi
        ]);

        return redirect()->back(); // Refresh halaman agar pesan muncul
    }
    public function widget()
{
    // LOGIKA BARU: Cari user yang role-nya 'admin' ATAU 'guru_bk'
    // Mengambil satu orang saja sebagai penerima pesan default
    $penerima = User::whereIn('role', ['admin', 'guru_bk'])->first();
    
    // Jika tidak ada Admin maupun Guru BK di database
    if(!$penerima) {
        return '<div class="p-4 text-center text-sm text-gray-500">Belum ada Guru BK/Admin yang terdaftar di sistem.</div>';
    }

    $lawanBicara = $penerima;
    $saya = Auth::id();

    // Ambil Chat
    $chats = Pesan::where(function ($q) use ($saya, $lawanBicara) {
        $q->where('pengirim_id', $saya)->where('penerima_id', $lawanBicara->id);
    })->orWhere(function ($q) use ($saya, $lawanBicara) {
        $q->where('pengirim_id', $lawanBicara->id)->where('penerima_id', $saya);
    })->orderBy('created_at', 'asc')->get();

    return view('chat.widget', compact('chats', 'lawanBicara'));
}
}