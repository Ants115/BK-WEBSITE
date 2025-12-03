<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BiodataStaf;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StafGuruController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        // TAMPILKAN: wali_kelas + staf_guru (+ optional guru_bk kalau mau)
        $query = User::with('biodataStaf')
            ->whereIn('role', ['wali_kelas', 'staf_guru']); 
            // kalau mau sekalian guru BK: ['wali_kelas', 'staf_guru', 'guru_bk']

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('biodataStaf', function ($q2) use ($search) {
                      $q2->where('nip', 'like', "%{$search}%")
                         ->orWhere('jabatan', 'like', "%{$search}%");
                  });
            });
        }

        $stafs = $query->orderBy('name')->paginate(15);
        return view('Admin.staf-guru.index', [
            'stafList' => $stafs,   // hasil paginate
            'search'   => $search,
        ]);
        
    }


    public function create(): View
    {
        return view('admin.staf-guru.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:6'],
            'nip'          => ['required', 'string', 'max:50', 'unique:biodata_staf,nip'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jabatan'      => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'staf_guru',
        ]);

        BiodataStaf::create([
            'user_id'      => $user->id,
            'nip'          => $validated['nip'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'jabatan'      => $validated['jabatan'] ?? 'Staf Guru',
        ]);

        return redirect()
            ->route('admin.staf-guru.index')
            ->with('success', 'Staf Guru berhasil ditambahkan.');
    }

    public function edit(User $stafGuru): View
    {
        abort_unless($stafGuru->role === 'staf_guru', 404);

        $stafGuru->load('biodataStaf');

        return view('admin.staf-guru.edit', compact('stafGuru'));
    }

    public function update(Request $request, User $stafGuru): RedirectResponse
    {
        abort_unless($stafGuru->role === 'staf_guru', 404);

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email,' . $stafGuru->id],
            'password'     => ['nullable', 'string', 'min:6'],
            'nip'          => ['required', 'string', 'max:50', 'unique:biodata_staf,nip,' . optional($stafGuru->biodataStaf)->id],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jabatan'      => ['nullable', 'string', 'max:100'],
        ]);

        $stafGuru->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password']
                ? Hash::make($validated['password'])
                : $stafGuru->password,
        ]);

        if ($stafGuru->biodataStaf) {
            $stafGuru->biodataStaf->update([
                'nip'          => $validated['nip'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'jabatan'      => $validated['jabatan'] ?? 'Staf Guru',
            ]);
        }

        return redirect()
            ->route('admin.staf-guru.index')
            ->with('success', 'Data Staf Guru berhasil diperbarui.');
    }

    public function destroy(User $stafGuru): RedirectResponse
    {
        abort_unless($stafGuru->role === 'staf_guru', 404);

        $stafGuru->delete();

        return redirect()
            ->route('admin.staf-guru.index')
            ->with('success', 'Staf Guru berhasil dihapus.');
    }
}
