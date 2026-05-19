<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GithubProfileController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::post('/github/import', [GithubProfileController::class, 'importProfile'])->name('import-profile');
});

require __DIR__.'/settings.php';
