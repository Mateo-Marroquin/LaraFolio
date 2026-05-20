<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GithubProfileController;
use App\Http\Controllers\MetricsController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::post('/github/import', [GithubProfileController::class, 'importProfile'])->name('import-profile');
    Route::get('/metrics', [MetricsController::class, 'index'])->name('metrics');
    Route::get('/profile', [GithubProfileController::class, 'index'])->name('github-profile');
});

require __DIR__.'/settings.php';
