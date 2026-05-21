<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\GithubRepositoryController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\ResumePdfController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GithubProfileController;
use App\Http\Controllers\MetricsController;

Route::view('/', 'welcome')->name('home');

//Route::middleware(['auth', 'verified'])->group(function () {
//    Route::view('dashboard', 'dashboard')->name('dashboard');
//    Route::post('/github/import', [GithubProfileController::class, 'importProfile'])->name('import-profile');
//    Route::get('/profile', [GithubProfileController::class, 'index'])->name('github-profile');
//});

Route::middleware(['auth', 'verified'])->group(function () {

    // 🔀 Al loguearte, /dashboard te rebota automáticamente a tu portafolio público /user/tu-usuario
    Route::get('dashboard', function () {
        $user = Auth::user();
        $username = $user->githubProvider?->username
            ?? $user->githubProvider()->where('provider', 'github')->value('username');

        //dd($user->load('githubProvider'));
        if (!$username) {
            return redirect()->route('home')->withErrors(['error' => 'No se encontró un perfil de GitHub vinculado.']);
        }

        return redirect()->route('public.profile', $username);
    })->name('dashboard');

//    Route::post('/github/import', [GithubProfileController::class, 'importProfile'])->name('import-profile');
//    Route::get('/profile', [GithubProfileController::class, 'index'])->name('github-profile');
});

Route::get('/search', [GithubProfileController::class, 'searchPublicProfile'])->name('profiles.search');

Route::get('/user/{username}', [GithubProfileController::class, 'showPublicProfile'])->name('public.profile');

Route::get('/user/{username}/repositories', [GithubRepositoryController::class, 'showPublicRepositories'])->name('public.repositories');

Route::get('/user/{username}/metrics', [MetricsController::class, 'index'])->name('metrics');

Route::get('/user/{username}/contact', [ContactController::class, 'show'])->name('public.contact');

Route::post('/user/{username}/contact', [ContactController::class, 'send'])->name('public.contact.send');

//Route::get('/user/{username}/download-pdf', [ResumePdfController::class, 'download'])->name('public.download.pdf');

Route::get('/user/{username}/resume', [ResumePdfController::class, 'showPreview'])->name('public.resume.preview');

Route::get('/user/{username}/resume/download', [ResumePdfController::class, 'download'])->name('public.resume.download');


Route::get('/auth/github/redirect', [OAuthController::class, 'redirect'])->name('auth.github.redirect');
Route::get('/auth/github/callback', [OAuthController::class, 'callback']);

require __DIR__.'/settings.php';
require __DIR__.'/settings.php';
