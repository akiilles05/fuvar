<?php

use App\Http\Controllers\Admin\Munkak as AdminMunkak;
use App\Http\Controllers\CarrierAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Fuvarozo\Munkak;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/login', [CarrierAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CarrierAuthController::class, 'login']);
Route::post('/logout', [CarrierAuthController::class, 'logout'])->name('logout');

Route::middleware('auth:fuvarozo')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/fuvarozo/munkak/{id}/statusz', [Munkak::class, 'updateStatus'])->name('fuvarozo.munkak.updateStatus');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('munkak', [AdminMunkak::class, 'index'])->name('munkak.index');
        Route::post('munkak', [AdminMunkak::class, 'store'])->name('munkak.store');
        Route::patch('munkak/{id}', [AdminMunkak::class, 'update'])->name('munkak.update');
        Route::delete('munkak/{id}', [AdminMunkak::class, 'destroy'])->name('munkak.destroy');
        Route::patch('munkak/{id}/assign', [AdminMunkak::class, 'assignFuvarozo'])->name('munkak.assign');
    });
});

require __DIR__.'/settings.php';
