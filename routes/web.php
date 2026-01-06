<?php

use App\Http\Controllers\IncomingGoodsController;
use App\Http\Controllers\OutgoingGoodsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match ($user->role) {
            'super_adm', 'admin' => redirect()->route('dashboard'),
            'staff' => redirect()->route('incoming-goods.index'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// ============================================
// AUTHENTICATION ROUTES (Fortify handles these)
// ============================================
// Login: /login
// Register: /register
// Logout: /logout
// Password Reset: /forgot-password, /reset-password

// ============================================
// DASHBOARD ROUTES
// ============================================
// Akses: super_adm, admin
Route::middleware(['auth', 'role:super_adm,admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
});

// ============================================
// INCOMING GOODS ROUTES (Barang Masuk)
// ============================================
// Akses: super_adm, admin, staff (semua role)
Route::middleware(['auth', 'role:super_adm,admin,staff'])->group(function () {
    Route::resource('incoming-goods', IncomingGoodsController::class);
    Route::get('/barang-masuk', [IncomingGoodsController::class, 'index'])->name('barang-masuk.index');
});

// ============================================
// OUTGOING GOODS ROUTES (Barang Keluar)
// ============================================
// Akses: super_adm, admin, staff (semua role)
Route::middleware(['auth', 'role:super_adm,admin,staff'])->group(function () {
    Route::get('/outgoing-goods/export-pdf', [OutgoingGoodsController::class, 'exportPdf'])->name('outgoing-goods.export-pdf');
    Route::resource('outgoing-goods', OutgoingGoodsController::class);
    Route::get('/barang-keluar', [OutgoingGoodsController::class, 'index'])->name('barang-keluar.index');
});

// ============================================
// ANALISIS ROUTES
// ============================================
// Akses: super_adm, admin
Route::middleware(['auth', 'role:super_adm,admin'])->group(function () {
    Route::prefix('analisis')->name('analisis.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])
            ->name('index');
    });
});

// ============================================
// REPORTS ROUTES (Laporan)
// ============================================
// Akses: super_adm saja
Route::middleware(['auth', 'role:super_adm'])->group(function () {
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::post('/export', [ReportController::class, 'export'])->name('export');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
    });
    
    // Alias untuk kompatibilitas
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
});

// ============================================
// USER MANAGEMENT ROUTES (Manajemen Pengguna)
// ============================================
// Akses: super_adm saja
Route::middleware(['auth', 'role:super_adm'])->group(function () {
    Route::resource('user-management', UserManagementController::class);
    Route::post('/user-management/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('user-management.reset-password');
    Route::post('/user-management/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('user-management.toggle-status');
    
    // Alias untuk kompatibilitas
    Route::get('/manajemen-pengguna', [UserManagementController::class, 'index'])->name('manajemen-pengguna.index');
});



// ============================================
// Dashboard Routes
// ============================================

Route::middleware(['auth', 'role:super_adm,admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index'); 
});
