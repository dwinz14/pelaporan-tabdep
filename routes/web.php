<?php

use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\CabangController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\RegistrasiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Akunting\AkuntingPeriodeController;
use App\Http\Controllers\Akunting\ExportController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Kepala\KepalaPeriodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pic\LaporanController;
use App\Http\Controllers\Pic\PencatatanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', fn() => redirect()->route('login'));
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:3,1');
});

// Halaman pending (setelah register berhasil)
Route::get('/register/pending', fn() => view('auth.register-pending'))
    ->name('register.pending');

// Auth routes (dari Breeze)
require __DIR__ . '/auth.php';

// Protected routes
Route::middleware('auth')->group(function () {

    // PIC Cabang
    Route::middleware('role:pic_cabang')
        ->prefix('pic')
        ->name('pic.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'pic'])->name('dashboard');
            Route::get('/pencatatan',               [PencatatanController::class, 'index'])->name('pencatatan.index');
            Route::get('/laporan/{periode}',        [LaporanController::class, 'edit'])->name('laporan.edit');
            Route::get('/riwayat',                  [LaporanController::class, 'riwayat'])->name('riwayat.index');
        });

    // Akunting Pusat
    Route::middleware('role:akunting')
        ->prefix('akunting')->name('akunting.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'akunting'])->name('dashboard');
            Route::get('/periode', [AkuntingPeriodeController::class, 'index'])->name('periode.index');
            Route::get('/periode/{periode}', [AkuntingPeriodeController::class, 'show'])->name('periode.show');
            Route::get('/periode/{periode}/export', [ExportController::class, 'exportPeriode'])->name('periode.export');
        });

    // Kepala Operasional
    Route::middleware('role:kepala_operasional')
        ->prefix('kepala')->name('kepala.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'kepala'])->name('dashboard');
            Route::get('/periode', [KepalaPeriodeController::class, 'index'])->name('periode.index');
            Route::get('/periode/{periode}', [KepalaPeriodeController::class, 'show'])->name('periode.show');
            Route::patch('/periode/{periode}/finalize', [KepalaPeriodeController::class, 'finalize'])->name('periode.finalize');
        });

    // Super Admin
    Route::middleware('role:super_admin')
        ->prefix('admin')->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

            // Manajemen Cabang
            Route::get('/cabang',                   [CabangController::class, 'index'])->name('cabang.index');
            Route::get('/cabang/create',             [CabangController::class, 'create'])->name('cabang.create');
            Route::post('/cabang',                   [CabangController::class, 'store'])->name('cabang.store');
            Route::get('/cabang/{cabang}/edit',      [CabangController::class, 'edit'])->name('cabang.edit');
            Route::put('/cabang/{cabang}',           [CabangController::class, 'update'])->name('cabang.update');
            Route::patch('/cabang/{cabang}/toggle',  [CabangController::class, 'toggleActive'])->name('cabang.toggle');

            // Manajemen User
            Route::get('/user',                      [UserController::class, 'index'])->name('user.index');
            Route::get('/user/create',               [UserController::class, 'create'])->name('user.create');
            Route::post('/user',                     [UserController::class, 'store'])->name('user.store');
            Route::get('/user/{user}/edit',          [UserController::class, 'edit'])->name('user.edit');
            Route::put('/user/{user}',               [UserController::class, 'update'])->name('user.update');
            Route::patch('/user/{user}/toggle',      [UserController::class, 'toggleActive'])->name('user.toggle');
            Route::post('/user/{user}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
            Route::post('/user/bulk-reset-password', [UserController::class, 'bulkResetPassword'])->name('user.bulk-reset-password');
            Route::patch('/user/bulk-deactivate',     [UserController::class, 'bulkDeactivate'])->name('user.bulk-deactivate');

            // register user
            Route::get('/registrasi',                       [RegistrasiController::class, 'index'])->name('registrasi.index');
            Route::patch('/registrasi/{user}/approve',       [RegistrasiController::class, 'approve'])->name('registrasi.approve');
            Route::patch('/registrasi/{user}/reject',        [RegistrasiController::class, 'reject'])->name('registrasi.reject');

            // Manajemen Periode
            Route::get('/periode',          [PeriodeController::class, 'index'])->name('periode.index');
            Route::get('/periode/create',   [PeriodeController::class, 'create'])->name('periode.create');
            Route::post('/periode',         [PeriodeController::class, 'store'])->name('periode.store');

            // audit
            Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');

            // import
            Route::get('/import',  [ImportController::class, 'index'])->name('import.index');
            Route::post('/import', [ImportController::class, 'store'])->name('import.store');
        });
});

require __DIR__ . '/auth.php';
