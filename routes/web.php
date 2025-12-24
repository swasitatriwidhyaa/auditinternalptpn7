<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\FindingController;
use App\Http\Controllers\RiwayatAuditController; // Tambahkan ini

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    
    // ==========================
    // 1. Audit Routes
    // ==========================
    Route::get('/audit/create', [AuditController::class, 'create'])->name('audit.create');
    Route::post('/audit/store', [AuditController::class, 'store'])->name('audit.store');
    Route::get('/audit/{id}', [AuditController::class, 'show'])->name('audit.show');

    // ==========================
    // 2. Request & Approval Routes
    // ==========================
    Route::get('/audit/request/new', [AuditController::class, 'requestForm'])->name('audit.request.form');
    Route::post('/audit/request/submit', [AuditController::class, 'submitRequest'])->name('audit.submit_request');
    Route::post('/audit/{id}/approve', [AuditController::class, 'approveAudit'])->name('audit.approve');

    // ==========================
    // 3. Finding Routes
    // ==========================
    Route::post('/finding/store', [FindingController::class, 'store'])->name('finding.store');
    Route::post('/finding/{id}/response', [FindingController::class, 'response'])->name('finding.response');
    
    // Rute perbaikan (Verify & Reopen)
    Route::post('/finding/{id}/verify', [FindingController::class, 'verify'])->name('finding.verify'); 
    Route::post('/finding/{id}/reopen', [FindingController::class, 'reopen'])->name('finding.reopen'); 

    // ==========================
    // 4. Riwayat Unit Routes (BARU)
    // ==========================
    // Halaman daftar semua unit
    Route::get('/riwayat-unit', [RiwayatAuditController::class, 'index'])->name('riwayat.index');
    // Halaman detail riwayat spesifik per unit
    Route::get('/riwayat-unit/{id}', [RiwayatAuditController::class, 'show'])->name('riwayat.show');

});