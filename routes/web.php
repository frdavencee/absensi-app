<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| REDIRECT ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| AUTH MIDDLEWARE GROUP
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [AbsensiController::class, 'dashboard'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ABSENSI
    |--------------------------------------------------------------------------
    */
    Route::post('/absen/masuk', [AbsensiController::class, 'masuk'])->name('absen.masuk');
    Route::post('/absen/pulang', [AbsensiController::class, 'pulang'])->name('absen.pulang');
    Route::post('/izin', [AbsensiController::class, 'izin'])->name('izin');
    Route::get('/data-absensi', [AbsensiController::class, 'dataAbsensi'])->name('data.absensi');
    Route::get('/export-absensi', [AbsensiController::class, 'exportExcel'])->name('export.absensi');
    Route::get('/pengajuan', [AbsensiController::class, 'pengajuan'])->name('pengajuan');
    Route::post('/approve/{id}', [AbsensiController::class, 'approve'])->name('approve');
    Route::delete('/pengajuan/{id}', [AbsensiController::class, 'destroyPengajuan'])->name('pengajuan.destroy');
    Route::delete('/absensi/{id}', [AbsensiController::class, 'destroyAbsensi'])->name('absensi.destroy');

    /*
    |--------------------------------------------------------------------------
    | KARYAWAN / USER MANAGEMENT (ADMIN ONLY)
    |--------------------------------------------------------------------------
    */
    Route::resource('karyawan', UserController::class)->middleware('role:admin');

    /*
    |--------------------------------------------------------------------------
    | JADWAL (ADMIN CRUD)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
        Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
        Route::post('/jadwal/import', [JadwalController::class, 'import'])->name('jadwal.import');
        Route::get('/jadwal/{jadwal}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
        Route::put('/jadwal/{jadwal}', [JadwalController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | JADWAL INDEX (SEMUA USER YANG LOGIN)
    |--------------------------------------------------------------------------
    */
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

    /*
    |--------------------------------------------------------------------------
    | NOTIFIKASI
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});