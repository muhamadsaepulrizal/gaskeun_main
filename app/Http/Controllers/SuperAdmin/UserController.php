<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PangkalanProfile;
use App\Models\ProfilAgen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function dashboard()
    {
        $totalUsers  = User::count();
        $totalAktif  = User::where('status_aktif', true)->count();
        $totalRoles  = Role::count();
        
        $totalAgen = \App\Models\ProfilAgen::count();
        $totalPangkalan = \App\Models\PangkalanProfile::count();
        
        // Kecamatan kritis = jumlah kecamatan yang punya keluhan dengan status 'Masuk' atau 'Diproses' lebih dari jumlah tertentu, atau ambil dari HeatmapSnapshot.
        $kecamatanKritis = \App\Models\HeatmapSnapshot::whereDate('tanggal_snapshot', now()->toDateString())
            ->where('level_risiko', 'Kritis')
            ->count();
            
        $ringkasanWilayah = \App\Models\HeatmapSnapshot::with('kecamatan')
            ->whereDate('tanggal_snapshot', now()->toDateString())
            ->orderByDesc('skor_heatmap')
            ->take(5)
            ->get();
            
        $donutAman = \App\Models\Kecamatan::count() - (\App\Models\HeatmapSnapshot::whereDate('tanggal_snapshot', now()->toDateString())->whereIn('level_risiko', ['Rawan', 'Kritis'])->count());
        $donutWaspada = \App\Models\HeatmapSnapshot::whereDate('tanggal_snapshot', now()->toDateString())->where('level_risiko', 'Rawan')->count();
        $donutKritis = $kecamatanKritis;
        $totalKecamatan = \App\Models\Kecamatan::count();
            
        $recentLogs  = \Spatie\Activitylog\Models\Activity::latest()->take(5)->get();
        
        return view('superadmin.dashboard', compact(
            'totalUsers', 'totalAktif', 'totalRoles', 'recentLogs',
            'totalAgen', 'totalPangkalan', 'kecamatanKritis',
            'ringkasanWilayah', 'donutAman', 'donutWaspada', 'donutKritis', 'totalKecamatan'
        ));
    }

    /**
     * Daftar user dengan filter search & status yang terhubung ke DB
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'profilAgen', 'pangkalanProfile']);

        // Filter search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('status_aktif', $status === 'aktif' ? true : false);
        }

        // Filter role
        if ($role = $request->input('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $role));
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('superadmin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles  = Role::all();
        $agens  = User::role('Agen LPG')->where('status_aktif', true)->get(); // Untuk BR-02
        $kecamatans = \App\Models\Kecamatan::orderBy('nama_kecamatan')->get();
        return view('superadmin.users.create', compact('roles', 'agens', 'kecamatans'));
    }

    /**
     * BR-01: Simpan ke tabel users + profil dalam SATU transaksi atomik
     * BR-02: Jika role Pangkalan, wajib pilih Agen Pembina
     */
    public function store(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'nullable|email|unique:users',
            'password' => 'nullable|string|min:8',
            'role'     => 'required|exists:roles,name',
        ];

        // Validation for location when creating Agen or Pangkalan
        if (in_array($request->role, ['Agen LPG', 'Pangkalan LPG'])) {
            $rules['alamat'] = 'required|string';
            $rules['kecamatan_id'] = 'required|exists:kecamatans,id';
            $rules['desa_kelurahan_id'] = 'required|exists:desas,id';
            $rules['latitude'] = 'required|numeric';
            $rules['longitude'] = 'required|numeric';
            
            if ($request->role === 'Pangkalan LPG') {
                $rules['agen_pembina_id'] = 'required|exists:users,id';
            }
        }

        $request->validate($rules, [
            'agen_pembina_id.required' => 'Agen Pembina wajib dipilih untuk akun Pangkalan LPG.',
            'kecamatan_id.required' => 'Kecamatan wajib diisi untuk role ini.',
            'desa_kelurahan_id.required' => 'Desa/Kelurahan wajib diisi untuk role ini.',
            'latitude.required' => 'Latitude wajib diisi. Silakan pilih lokasi di peta.',
            'longitude.required' => 'Longitude wajib diisi. Silakan pilih lokasi di peta.',
        ]);

        DB::transaction(function () use ($request) {
            $isDefaultPassword = empty($request->password);
            
            // Simpan ke tabel users
            $user = User::create([
                'name'     => $request->name,
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password ?? 'password'),
                'status_aktif' => true,
                'force_password_change' => $isDefaultPassword,
            ]);

            $user->assignRole($request->role);

            // BR-01: Simpan ke tabel profil turunan
            if ($request->role === 'Agen LPG') {
                ProfilAgen::create([
                    'user_id'           => $user->id,
                    'nama_agen'         => $request->name,
                    'kontak'            => $request->kontak ?? null,
                    'alamat'            => $request->alamat ?? null,
                    'kecamatan_id'      => $request->kecamatan_id,
                    'desa_kelurahan_id' => $request->desa_kelurahan_id,
                    'latitude'          => $request->latitude,
                    'longitude'         => $request->longitude,
                ]);
            }

            if ($request->role === 'Pangkalan LPG') {
                PangkalanProfile::create([
                    'user_id'           => $user->id,
                    'agen_pembina_id'   => $request->agen_pembina_id, // BR-02
                    'nama_pangkalan'    => $request->name,
                    'kontak'            => $request->kontak ?? null,
                    'alamat'            => $request->alamat ?? null,
                    'kecamatan_id'      => $request->kecamatan_id,
                    'desa_kelurahan_id' => $request->desa_kelurahan_id,
                    'latitude'          => $request->latitude,
                    'longitude'         => $request->longitude,
                ]);
            }

            activity()->performedOn($user)->log('Created user: ' . $user->username . ' with role ' . $request->role);
        });

        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $user->load('pangkalanProfile', 'profilAgen');
        $roles = Role::all();
        $agens = User::role('Agen LPG')->where('status_aktif', true)->get();
        $kecamatans = \App\Models\Kecamatan::orderBy('nama_kecamatan')->get();
        return view('superadmin.users.edit', compact('user', 'roles', 'agens', 'kecamatans'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email'    => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|exists:roles,name',
        ];

        // Validation for location when creating Agen or Pangkalan
        if (in_array($request->role, ['Agen LPG', 'Pangkalan LPG'])) {
            $rules['alamat'] = 'required|string';
            $rules['kecamatan_id'] = 'required|exists:kecamatans,id';
            $rules['desa_kelurahan_id'] = 'required|exists:desas,id';
            $rules['latitude'] = 'required|numeric';
            $rules['longitude'] = 'required|numeric';
            
            if ($request->role === 'Pangkalan LPG') {
                $rules['agen_pembina_id'] = 'required|exists:users,id';
            }
        }

        $request->validate($rules, [
            'agen_pembina_id.required' => 'Agen Pembina wajib dipilih untuk akun Pangkalan LPG.',
            'kecamatan_id.required' => 'Kecamatan wajib diisi untuk role ini.',
            'desa_kelurahan_id.required' => 'Desa/Kelurahan wajib diisi untuk role ini.',
            'latitude.required' => 'Latitude wajib diisi. Silakan pilih lokasi di peta.',
            'longitude.required' => 'Longitude wajib diisi. Silakan pilih lokasi di peta.',
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->update([
                'name'     => $request->name,
                'username' => $request->username,
                'email'    => $request->email,
            ]);

            $user->syncRoles([$request->role]);

            // Update profil turunan jika ada
            if ($request->role === 'Agen LPG') {
                $user->profilAgen()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama_agen'         => $request->name,
                        'alamat'            => $request->alamat ?? null,
                        'kecamatan_id'      => $request->kecamatan_id,
                        'desa_kelurahan_id' => $request->desa_kelurahan_id,
                        'latitude'          => $request->latitude,
                        'longitude'         => $request->longitude,
                    ]
                );
            }

            if ($request->role === 'Pangkalan LPG') {
                $user->pangkalanProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'agen_pembina_id'   => $request->agen_pembina_id, 
                        'nama_pangkalan'    => $request->name,
                        'alamat'            => $request->alamat ?? null,
                        'kecamatan_id'      => $request->kecamatan_id,
                        'desa_kelurahan_id' => $request->desa_kelurahan_id,
                        'latitude'          => $request->latitude,
                        'longitude'         => $request->longitude,
                    ]
                );
            }

            activity()->performedOn($user)->log('Updated user: ' . $user->username);
        });

        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Soft Deactivate: Ubah status_aktif = false + forced logout sesi aktif
     * Tidak menggunakan hard delete agar jejak audit terjaga
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }

        DB::transaction(function () use ($user) {
            // Soft deactivate
            $user->update(['status_aktif' => false]);

            // Forced logout: hapus semua sesi aktif user ini
            DB::table('sessions')->where('user_id', $user->id)->delete();

            activity()->performedOn($user)->log('Deactivated user: ' . $user->username);
        });

        return redirect()->route('superadmin.users.index')
            ->with('success', "User {$user->name} berhasil dinonaktifkan. Sesi aktifnya telah dihapus.");
    }

    /**
     * Aktifkan kembali user yang nonaktif
     */
    public function activate(User $user)
    {
        $user->update(['status_aktif' => true]);
        activity()->performedOn($user)->log('Activated user: ' . $user->username);

        return redirect()->back()->with('success', "User {$user->name} berhasil diaktifkan kembali.");
    }

    /**
     * Reset password tanpa perlu tahu password lama
     * + Set flag force_password_change agar user wajib ganti password
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user->update([
            'password'              => Hash::make($request->password),
            'force_password_change' => true, // Paksa ganti password setelah login
        ]);

        activity()->performedOn($user)->log('Reset password for user: ' . $user->username);

        return redirect()->back()->with('success', 'Password direset. User wajib ganti password saat login berikutnya.');
    }
}
