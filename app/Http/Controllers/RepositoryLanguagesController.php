<?php

namespace App\Http\Controllers;

use App\Models\RepositoryLanguages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function getRepositoryLanguages()
    {
        $repositories = auth()->user()->githubRepositories;

        if ($repositories->isEmpty()) {
            return response()->json(['message' => 'No hay repositorios que procesar.'], 200);
        }

        $userId = auth()->id();

        foreach ($repositories as $repo) {
            if (! $repo->languages_url) {
                continue;
            }

            $response = Http::withHeaders(['Accept' => 'application/vnd.github+json'])
                ->withToken(config('services.github.token'))
                ->get($repo->languages_url);

            if ($response->successful()) {
                $languages = $response->json();

                foreach ($languages as $languageName => $bytesCount) {

                    RepositoryLanguages::updateOrCreate(
                        [
                            'user_id'              => $userId,
                            'github_repository_id' => $repo->id,
                            'name'                 => $languageName,
                        ],
                        [
                            'bytes'                => $bytesCount,
                        ]
                    );
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Todos los lenguajes por repositorio han sido guardados y actualizados correctamente.'
        ]);
    }
}
