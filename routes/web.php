<?php

use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\CabangController;
use App\Http\Controllers\Admin\DatabaseMaintenanceController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\RegistrasiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Akunting\AkuntingPeriodeController;
use App\Http\Controllers\Akunting\ExportController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Kepala\KepalaPeriodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
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

    // ── Profile (semua role) ──────────────────────────────────
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',         [ProfileController::class, 'index'])->name('index');
        Route::put('/info',     [ProfileController::class, 'updateProfile'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });
    // ── Notifications (semua role authenticated) ──────────────
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',              [NotificationController::class, 'index'])->name('index');
        Route::get('/{id}/read',     [NotificationController::class, 'markAsRead'])->name('read');
        Route::patch('/read-all',    [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}',       [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/',           [NotificationController::class, 'destroyAll'])->name('destroy-all');
    });

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
            Route::patch('/user/bulk-activate',       [UserController::class, 'bulkActivate'])->name('user.bulk-activate');

            // register user
            Route::get('/registrasi',                       [RegistrasiController::class, 'index'])->name('registrasi.index');
            Route::patch('/registrasi/{user}/approve',       [RegistrasiController::class, 'approve'])->name('registrasi.approve');
            Route::patch('/registrasi/{user}/reject',        [RegistrasiController::class, 'reject'])->name('registrasi.reject');

            // Manajemen Periode
            Route::get('/periode',          [PeriodeController::class, 'index'])->name('periode.index');
            Route::get('/periode/create',   [PeriodeController::class, 'create'])->name('periode.create');
            Route::post('/periode',         [PeriodeController::class, 'store'])->name('periode.store');
            Route::post('/periode/generate-suggested',    [PeriodeController::class, 'generateSuggested'])->name('periode.generate-suggested');
            Route::get('/periode/{periode}',              [PeriodeController::class, 'show'])->name('periode.show');

            // audit
            Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');

            // import
            Route::prefix('import')->name('import.')->group(function () {
                Route::get('/',                 [ImportController::class, 'index'])->name('index');
                Route::get('/template',         [ImportController::class, 'downloadTemplate'])->name('template');
                Route::post('/validate',        [ImportController::class, 'validateUpload'])->name('validate');
                Route::post('/confirm',         [ImportController::class, 'confirm'])->name('confirm');
                Route::post('/cancel',          [ImportController::class, 'cancel'])->name('cancel');
            });

            // database maintenance
            Route::prefix('database')->name('database.')->group(function () {
                Route::get('/',                          [DatabaseMaintenanceController::class, 'index'])->name('index');
                Route::post('/backup',                   [DatabaseMaintenanceController::class, 'backup'])->name('backup');
                Route::get('/backup/{filename}/download', [DatabaseMaintenanceController::class, 'download'])->name('download')
                    ->where('filename', '[a-zA-Z0-9._-]+');
                Route::delete('/backup/{filename}',      [DatabaseMaintenanceController::class, 'deleteBackup'])->name('delete-backup')
                    ->where('filename', '[a-zA-Z0-9._-]+');
                Route::post('/restore/upload',           [DatabaseMaintenanceController::class, 'uploadRestore'])->name('restore.upload');
                Route::post('/restore/confirm',          [DatabaseMaintenanceController::class, 'confirmRestore'])->name('restore.confirm');
                Route::post('/restore/dismiss',          [DatabaseMaintenanceController::class, 'dismissRestore'])->name('restore.dismiss');
            });
        });
});

require __DIR__ . '/auth.php';
