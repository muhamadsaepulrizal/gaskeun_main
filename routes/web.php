<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\RolePermissionController;
use App\Http\Controllers\SuperAdmin\ActivityLogController;

// Disperindag Controllers
use App\Http\Controllers\Disperindag\KecamatanController;
use App\Http\Controllers\Disperindag\DesaController;
use App\Http\Controllers\Disperindag\KkController;
use App\Http\Controllers\Disperindag\PendudukController;
use App\Http\Controllers\Disperindag\NelayanController;
use App\Http\Controllers\Disperindag\PetaniController;
use App\Http\Controllers\Disperindag\UmkmController;
use App\Http\Controllers\Disperindag\RumahTanggaSasaranController;
use App\Http\Controllers\DisperindagKeluhanController;

// Transaction Controllers
use App\Http\Controllers\AgenController;
use App\Http\Controllers\PangkalanController;

// Dashboard Controllers
use App\Http\Controllers\Dashboard\DisperindagController;
use App\Http\Controllers\Dashboard\PengawasController;

// Public Controllers
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicKeluhanController;

use App\Http\Controllers\RegisterController;

/*
|--------------------------------------------------------------------------
| Public Routes (Akses Bebas, Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('home');

// Keluhan Publik (Tanpa Login — Sistem OTP)
Route::get('/keluhan', [PublicKeluhanController::class, 'create'])->name('public.keluhan.create');
Route::get('/keluhan/get-pangkalans/{kecamatan_id}', [PublicKeluhanController::class, 'getPangkalans'])->name('public.keluhan.get-pangkalans');
Route::get('/keluhan/get-desas/{kecamatan_id}', [PublicKeluhanController::class, 'getDesas'])->name('public.keluhan.get-desas');
Route::post('/keluhan/otp-request', [PublicKeluhanController::class, 'requestOtp'])->name('public.keluhan.otp-request');
Route::post('/keluhan/otp-verify', [PublicKeluhanController::class, 'verifyOtp'])->name('public.keluhan.otp-verify');
Route::post('/keluhan', [PublicKeluhanController::class, 'store'])->name('public.keluhan.store');
Route::get('/keluhan/status', [PublicKeluhanController::class, 'status'])->name('public.keluhan.status');
Route::post('/keluhan/status', [PublicKeluhanController::class, 'checkStatus'])->name('public.keluhan.check-status');

// Peta GIS & Heatmap (Public)
Route::get('/peta', [PublicController::class, 'peta'])->name('public.peta');
Route::get('/heatmap', [PublicController::class, 'heatmap'])->name('public.heatmap');

// Beranda Publik (alias welcome)
Route::get('/publik/beranda', function () {
    return redirect()->route('home');
})->name('publik.beranda');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
// Route::post('/register', [RegisterController::class, 'register'])->name('register.post')->middleware('guest');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\ProfileController;

// Force Password Change & Profile Management
Route::middleware('auth')->group(function () {
    // Force Change Password
    Route::get('/ganti-password', [AuthController::class, 'showForceChangePassword'])->name('auth.force-change-password');
    Route::post('/ganti-password', [AuthController::class, 'forceChangePassword'])->name('auth.force-change-password.post');

    // General Change Password
    Route::get('/profil/ubah-password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profil/ubah-password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

/*
|--------------------------------------------------------------------------
| Dashboard Gateway (Login Redirect)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();
    $role = $user->roles->first();

    if ($role) {
        $baseRole = $role->base_role ?? $role->name;

        if ($baseRole === 'Super Admin')   return redirect()->route('superadmin.dashboard');
        if ($baseRole === 'Pengawas')      return redirect()->route('pengawas.dashboard');
        if ($baseRole === 'Disperindag')   return redirect()->route('disperindag.dashboard');
        if ($baseRole === 'Agen LPG')      return redirect()->route('agen.dashboard');
        if ($baseRole === 'Pangkalan LPG') return redirect()->route('pangkalan.dashboard');
        if ($baseRole === 'Publik') {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('home')->with('success', 'Masyarakat Umum (Publik) tidak perlu memiliki akun login.');
        }
    }

    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login')->with('error', 'Akses ditolak. Role Anda tidak dikenali oleh sistem.');
})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| Actor Specific Routes
|--------------------------------------------------------------------------
*/

// 1. Super Admin
Route::middleware(['auth', 'base_role:Super Admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');

    Route::get('roles', [RolePermissionController::class, 'index'])->name('roles.index');
    Route::post('roles', [RolePermissionController::class, 'storeRole'])->name('roles.store');
    Route::put('roles/{role}', [RolePermissionController::class, 'updateRole'])->name('roles.update');
    Route::delete('roles/{role}', [RolePermissionController::class, 'destroyRole'])->name('roles.destroy');
    Route::get('logs', [ActivityLogController::class, 'index'])->name('logs.index');
});

// 2. Disperindag
Route::middleware(['auth', 'base_role:Disperindag'])->prefix('disperindag')->name('disperindag.')->group(function () {
    Route::get('dashboard', [DisperindagController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('kecamatans', KecamatanController::class)->except(['show']);
    Route::resource('desas', DesaController::class)->except(['show']);
    
    // Data Konsumen Terdaftar
    Route::resource('konsumens', App\Http\Controllers\Disperindag\KonsumenController::class)->only(['index', 'edit', 'update', 'destroy']);

    // Keluhan Management & Verifikasi Tiket
    Route::get('keluhan', [DisperindagKeluhanController::class, 'index'])->name('keluhan.index');
    Route::get('keluhan/{keluhan}', [DisperindagKeluhanController::class, 'show'])->name('keluhan.show');
    Route::put('keluhan/{keluhan}', [DisperindagKeluhanController::class, 'update'])->name('keluhan.update');
    Route::post('keluhan/{keluhan}/tolak', [DisperindagKeluhanController::class, 'tolak'])->name('keluhan.tolak');
    Route::post('keluhan/{keluhan}/selesai', [DisperindagKeluhanController::class, 'selesai'])->name('keluhan.selesai');
});

// 3. Agen LPG
Route::middleware(['auth', 'base_role:Agen LPG'])->prefix('agen')->name('agen.')->group(function () {
    Route::get('dashboard', [AgenController::class, 'dashboard'])->name('dashboard');
    Route::get('profil', [AgenController::class, 'profil'])->name('profil');
    Route::put('profil', [AgenController::class, 'updateProfil'])->name('profil.update');

    // Kelola Data Pangkalan Binaan (CRUD + Buat Akun Otomatis)
    Route::resource('pangkalan-binaan', \App\Http\Controllers\Agen\PangkalanBinaanController::class)->except(['show', 'destroy']);

    // Transaksi Pengiriman LPG
    Route::get('pengiriman/create', [AgenController::class, 'pengirimanCreate'])->name('pengiriman.create');
    Route::post('pengiriman', [AgenController::class, 'pengirimanStore'])->name('pengiriman.store');
    Route::post('pengiriman/import', [AgenController::class, 'pengirimanImport'])->name('pengiriman.import');
    Route::get('pengiriman/status', [AgenController::class, 'pengirimanStatus'])->name('pengiriman.status');
});

// 4. Pangkalan LPG
Route::middleware(['auth', 'base_role:Pangkalan LPG'])->prefix('pangkalan')->name('pangkalan.')->group(function () {
    Route::get('dashboard', [PangkalanController::class, 'dashboard'])->name('dashboard');

    // Penerimaan LPG dari Agen
    Route::get('pengiriman', [PangkalanController::class, 'terimaPengiriman'])->name('pengiriman.index');
    Route::post('pengiriman/{pengiriman}/konfirmasi', [PangkalanController::class, 'konfirmasiPenerimaan'])->name('pengiriman.konfirmasi');
    Route::post('pengiriman/{pengiriman}/koreksi', [PangkalanController::class, 'ajukanKoreksi'])->name('pengiriman.koreksi');

    // Penyaluran ke Konsumen Sasaran
    Route::get('penyaluran/create', [PangkalanController::class, 'penyaluranCreate'])->name('penyaluran.create');
    Route::post('penyaluran', [PangkalanController::class, 'penyaluranStore'])->name('penyaluran.store');

    // Registrasi & Data Konsumen
    Route::get('/konsumen', [PangkalanController::class, 'konsumenIndex'])->name('konsumen.index');
    Route::get('/konsumen/search', [PangkalanController::class, 'searchKonsumen'])->name('konsumen.search');
    Route::get('/konsumen/create', [PangkalanController::class, 'konsumenCreate'])->name('konsumen.create');
    Route::post('/konsumen', [PangkalanController::class, 'konsumenStore'])->name('konsumen.store');

    // Monitoring Stok
    Route::get('stok', [PangkalanController::class, 'sisaStok'])->name('stok');
});

// 5. Pengawas (Gabungan Pimpinan Daerah & Hiswana Migas)
Route::middleware(['auth', 'base_role:Pengawas'])->prefix('pengawas')->name('pengawas.')->group(function () {
    Route::get('dashboard', [PengawasController::class, 'index'])->name('dashboard');
});
