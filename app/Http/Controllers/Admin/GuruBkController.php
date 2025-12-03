<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BiodataStaf;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class GuruBkController extends Controller
{
    /**
     * Daftar semua guru BK.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $gurus = User::where('role', 'guru_bk')
            ->with('biodataStaf')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('biodataStaf', function ($sub) use ($search) {
                      $sub->where('nip', 'like', "%{$search}%")
                          ->orWhere('nama_lengkap', 'like', "%{$search}%");
                  });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.guru-bk.index', compact('gurus', 'search'));
    }

    /**
     * Form tambah guru BK.
     */
    public function create(): View
    {
        return view('admin.guru-bk.create');
    }

    /**
     * Simpan guru BK baru.
     */
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
            'role'     => 'guru_bk',
        ]);

        BiodataStaf::create([
            'user_id'      => $user->id,
            'nip'          => $validated['nip'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'jabatan'      => $validated['jabatan'] ?? 'Guru BK',
        ]);

        return redirect()
            ->route('admin.guru-bk.index')
            ->with('success', 'Guru BK berhasil ditambahkan.');
    }

    /**
     * Form edit guru BK.
     */
    public function edit(User $guruBk): View
    {
        abort_unless($guruBk->role === 'guru_bk', 404);

        $guruBk->load('biodataStaf');

        return view('admin.guru-bk.edit', compact('guruBk'));
    }

    /**
     * Update data guru BK.
     */
    public function update(Request $request, User $guruBk): RedirectResponse
    {
        abort_unless($guruBk->role === 'guru_bk', 404);

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email,' . $guruBk->id],
            'password'     => ['nullable', 'string', 'min:6'],
            'nip'          => ['required', 'string', 'max:50', 'unique:biodata_staf,nip,' . optional($guruBk->biodataStaf)->id],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jabatan'      => ['nullable', 'string', 'max:100'],
        ]);

        $guruBk->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            // hanya update password kalau diisi
            'password' => $validated['password']
                ? Hash::make($validated['password'])
                : $guruBk->password,
        ]);

        if ($guruBk->biodataStaf) {
            $guruBk->biodataStaf->update([
                'nip'          => $validated['nip'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'jabatan'      => $validated['jabatan'] ?? 'Guru BK',
            ]);
        }

        return redirect()
            ->route('admin.guru-bk.index')
            ->with('success', 'Data guru BK berhasil diperbarui.');
    }

    /**
     * Hapus guru BK.
     */
    public function destroy(User $guruBk): RedirectResponse
    {
        abort_unless($guruBk->role === 'guru_bk', 404);

        // otomatis akan menghapus biodata_staf jika pakai foreign key cascade
        $guruBk->delete();

        return redirect()
            ->route('admin.guru-bk.index')
            ->with('success', 'Guru BK berhasil dihapus.');
    }

    public function biodataStaf()
{
    return $this->hasOne(\App\Models\BiodataStaf::class, 'user_id');
}

}
