<?php

use Illuminate\Support\Facades\Route;

// 0. IMPORT AUTH CONTROLLER
use App\Http\Controllers\AuthController;

// 1. IMPORT CONTROLLER ADMIN
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TarifController;
use App\Http\Controllers\Admin\AreaParkirController;
use App\Http\Controllers\Admin\KendaraanController;
use App\Http\Controllers\Admin\LogAktivitasController;

// 2. IMPORT CONTROLLER PETUGAS
use App\Http\Controllers\Petugas\PetugasDashboard;
use App\Http\Controllers\Petugas\TransaksiController;

// 3. IMPORT CONTROLLER OWNER
use App\Http\Controllers\Owner\DashboardController as OwnerDashboard;
use App\Http\Controllers\Owner\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect Halaman Utama (/) langsung ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// =========================================================================
// ROUTE AUTH / LOGIN & LOGOUT
// =========================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// =========================================================================
// ROUTE MODUL ADMIN (Khusus Role Admin)
// =========================================================================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Kendaraan (CRUD Lengkap)
    Route::get('/kendaraan', [KendaraanController::class, 'index'])->name('admin.kendaraan.index');
    Route::post('/kendaraan', [KendaraanController::class, 'store'])->name('admin.kendaraan.store');
    Route::put('/kendaraan/{id}', [KendaraanController::class, 'update'])->name('admin.kendaraan.update');
    Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy'])->name('admin.kendaraan.destroy');

    // Modul Area Parkir (CRUD Lengkap)
    Route::get('/area-parkir', [AreaParkirController::class, 'index'])->name('admin.area.index');
    Route::post('/area-parkir', [AreaParkirController::class, 'store'])->name('admin.area.store');
    Route::put('/area-parkir/{id}', [AreaParkirController::class, 'update'])->name('admin.area.update');
    Route::delete('/area-parkir/{id}', [AreaParkirController::class, 'destroy'])->name('admin.area.destroy');

    //Modul Tarif
    Route::get('/tarif', [TarifController::class, 'index'])->name('admin.tarif.index');
    Route::post('/tarif', [TarifController::class, 'store'])->name('admin.tarif.store');
    Route::put('/tarif/{id}', [TarifController::class, 'update'])->name('admin.tarif.update');
    Route::delete('/tarif/{id}', [TarifController::class, 'destroy'])->name('admin.tarif.destroy');

    // Route CRUD User
    Route::get('/user', [UserController::class, 'index'])->name('admin.user.index');
    Route::post('/user', [UserController::class, 'store'])->name('admin.user.store');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');

    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('admin.log.index');
});

// =========================================================================
// ROUTE MODUL PETUGAS (Khusus Role Petugas)
// =========================================================================
Route::prefix('petugas')->middleware(['auth', 'role:petugas'])->group(function () {
   
    // Penyesuaian nama rute agar cocok dengan file Blade Sidebar
    Route::get('/dashboard', [\App\Http\Controllers\Petugas\PetugasDashboardController::class, 'index'])->name('petugas.dashboard');
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('petugas.transaksi.index');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('petugas.transaksi.store');
    Route::put('/transaksi/keluar/{id}', [TransaksiController::class, 'prosesKeluar'])->name('petugas.transaksi.keluar');

    Route::get('/transaksi/cetak/{id}', [TransaksiController::class, 'cetakStruk'])->name('petugas.transaksi.cetak');
    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('petugas.log.index');
});

// =========================================================================
// ROUTE MODUL OWNER (Khusus Role Owner)
// =========================================================================
Route::prefix('owner')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [OwnerDashboard::class, 'index'])->name('owner.dashboard');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('owner.laporan.index');
});