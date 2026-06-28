<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::resource('assets', AssetController::class)->except(['show']);
    });

    Route::get('/requests', [AssetRequestController::class, 'index'])->name('requests.index');

    Route::post('/requests', [AssetRequestController::class, 'store'])
        ->middleware('role:employee')
        ->name('requests.store');

    Route::patch('/requests/{assetRequest}/status', [AssetRequestController::class, 'updateStatus'])
        ->middleware('role:manager,admin')
        ->name('requests.update-status');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
