<?php

namespace App\Http\Controllers;

use App\Models\RepositoryLanguages;
use App\Models\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RepositoryLanguagesController extends Controller
{
    public function index()
    {
        return RepositoryLanguages::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'github_repository_id' => ['required', 'exists:github_repositories'],
            'name' => ['required'],
            'bytes' => ['required', 'integer'],
        ]);

        return RepositoryLanguages::create($data);
    }

    public function show(RepositoryLanguages $repositoryLanguages)
    {
        return $repositoryLanguages;
    }

    public function update(Request $request, RepositoryLanguages $repositoryLanguages)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'github_repository_id' => ['required', 'exists:github_repositories'],
            'name' => ['required'],
            'bytes' => ['required', 'integer'],
        ]);

        $repositoryLanguages->update($data);

        return $repositoryLanguages;
    }

    public function destroy(RepositoryLanguages $repositoryLanguages)
    {
        $repositoryLanguages->delete();

        return response()->json();
    }

    public function getRepositoryLanguages($repositories, $userId = null)
    {
        if ($repositories->isEmpty()) {
            return false;
        }

        $token = null;
        if ($userId) {
            $provider = UserProvider::where('user_id', $userId)->where('provider', 'github')->first();
            if ($provider) {
                $token = $provider->token;
            }
        }

        if (!$token) {
            $token = auth()->check()
                ? auth()->user()->githubProvider?->token ?? config('services.github.token')
                : config('services.github.token');
        }

        foreach ($repositories as $repo) {
            if (!$repo->languages_url) {
                continue;
            }

            $requestBuilder = Http::withHeaders(['Accept' => 'application/vnd.github+json']);

            if ($token) {
                $requestBuilder->withToken($token);
            }

            $response = $requestBuilder->get($repo->languages_url);

            if ($response->successful()) {
                $languages = $response->json();

                foreach ($languages as $languageName => $bytesCount) {
                    RepositoryLanguages::updateOrCreate(
                        [
                            'user_id' => $userId,
                            'github_repository_id' => $repo->id,
                            'name' => $languageName,
                        ],
                        [
                            'bytes' => $bytesCount,
                        ]
                    );
                }
            }
        }

        return true;
    }

    public function syncLanguagesForRepositories($repositories, $userId = null)
    {
        if ($repositories->isEmpty()) {
            return false;
        }

        $token = null;
        if ($userId) {
            $provider = UserProvider::where('user_id', $userId)->where('provider', 'github')->first();
            if ($provider) {
                $token = $provider->token;
            }
        }

        if (!$token) {
            $token = auth()->check()
                ? auth()->user()->githubProvider?->token ?? config('services.github.token')
                : config('services.github.token');
        }

        foreach ($repositories as $repo) {
            if (!$repo->languages_url) {
                continue;
            }

            $requestBuilder = Http::withHeaders(['Accept' => 'application/vnd.github+json']);

            if ($token) {
                $requestBuilder->withToken($token);
            }

            $response = $requestBuilder->get($repo->languages_url);

            if ($response->successful()) {
                $languages = $response->json();

                RepositoryLanguages::where('github_repository_id', $repo->id)->delete();

                foreach ($languages as $languageName => $bytesCount) {
                    RepositoryLanguages::create([
                        'user_id' => $userId,
                        'github_repository_id' => $repo->id,
                        'name' => $languageName,
                        'bytes' => $bytesCount,
                    ]);
                }
            } else {
                Log::error("Error al conocer lenguajes del repositorio ID {$repo->id}: " . $response->status());
            }
        }

        return true;
    }
}
