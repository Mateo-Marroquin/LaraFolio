<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\GithubRepositoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GithubProfileController;
use App\Http\Controllers\MetricsController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::post('/github/import', [GithubProfileController::class, 'importProfile'])->name('import-profile');
    Route::get('/profile', [GithubProfileController::class, 'index'])->name('github-profile');
});

Route::get('/search', [GithubProfileController::class, 'searchPublicProfile'])->name('profiles.search');

Route::get('/user/{username}', [GithubProfileController::class, 'showPublicProfile'])->name('public.profile');

Route::get('/user/{username}/repositories', [GithubRepositoryController::class, 'showPublicRepositories'])->name('public.repositories');

Route::get('/user/{username}/metrics', [MetricsController::class, 'index'])->name('metrics');

Route::get('/user/{username}/contact', [ContactController::class, 'show'])->name('public.contact');

Route::post('/user/{username}/contact', [ContactController::class, 'send'])->name('public.contact.send');

require __DIR__.'/settings.php';
require __DIR__.'/settings.php';
