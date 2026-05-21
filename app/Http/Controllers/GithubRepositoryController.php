<?php

namespace App\Http\Controllers;

use App\Models\GithubRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Log;

class GithubRepositoryController extends Controller
{
    public function index()
    {
        return GithubRepository::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'github_repo_id' => ['required'],
            'name' => ['required'],
            'full_name' => ['required'],
            'html_url' => ['required'],
            'description' => ['nullable'],
            'primary_languaje' => ['nullable'],
            'stars_count' => ['required', 'integer'],
            'forks_count' => ['required', 'integer'],
            'is_fork' => ['boolean'],
            'github_updated_at' => ['nullable', 'date'],
        ]);

        return GithubRepository::create($data);
    }

    public function show(GithubRepository $githubRepository)
    {
        return $githubRepository;
    }

    public function update(Request $request, GithubRepository $githubRepository)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'github_repo_id' => ['required'],
            'name' => ['required'],
            'full_name' => ['required'],
            'html_url' => ['required'],
            'description' => ['nullable'],
            'primary_languaje' => ['nullable'],
            'stars_count' => ['required', 'integer'],
            'forks_count' => ['required', 'integer'],
            'is_fork' => ['boolean'],
            'github_updated_at' => ['nullable', 'date'],
        ]);

        $githubRepository->update($data);

        return $githubRepository;
    }

    public function destroy(GithubRepository $githubRepository)
    {
        $githubRepository->delete();

        return response()->json();
    }

    public function syncRepositories($username, $userId = null)
    {
        $token = config('services.github.token');
        $url = "https://api.github.com/users/{$username}/repos";

        if (!$userId) {
            $userId = \App\Models\UserProvider::where('provider', 'github')
                ->where('username', $username)
                ->value('user_id');
        }

        if (auth()->check() && auth()->id() == $userId) {
            $provider = auth()->user()->githubProvider ?? \App\Models\UserProvider::where('user_id', auth()->id())->where('provider', 'github')->first();
            if ($provider) {
                $token = $provider->token;
                $url = "https://api.github.com/user/repos";
            }
        }

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
        ])
            ->withToken($token)
            ->get($url, [
                'per_page' => 100,
                'sort' => 'updated',
                'type' => 'all'
            ]);

        if ($response->failed()) {
            Log::error("Fallo al consultar repositorios de GitHub para: {$username}", ['status' => $response->status()]);
            return false;
        }

        $repositories = $response->json();

        foreach ($repositories as $repo) {
            GithubRepository::updateOrCreate(
                [
                    'github_repo_id' => $repo['id'],
                ],
                [
                    'user_id' => $userId,
                    'name' => $repo['name'],
                    'full_name' => $repo['full_name'],
                    'html_url' => $repo['html_url'],
                    'description' => $repo['description'] ?? null,
                    'primary_language' => $repo['language'] ?? null,
                    'stars_count' => $repo['stargazers_count'] ?? 0,
                    'forks_count' => $repo['forks_count'] ?? 0,
                    'is_fork' => $repo['fork'],
                    'is_private' => $repo['private'],
                    'languages_url' => $repo['languages_url'],
                    'github_updated_at' => Carbon::parse($repo['updated_at']),
                ]
            );
        }

        $savedRepositories = GithubRepository::where('user_id', $userId)
            ->where('full_name', 'like', $username . '/%')
            ->get();

        $languageController = new RepositoryLanguagesController();
        $languageController->syncLanguagesForRepositories($savedRepositories, $userId);

        return true;
    }

    public function showPublicRepositories($username)
    {
        $isOwner = auth()->check() && (
                auth()->user()->githubProvider?->username === $username ||
                \App\Models\UserProvider::where('user_id', auth()->id())->where('provider', 'github')->where('username', $username)->exists()
            );

        $userId = \App\Models\UserProvider::where('provider', 'github')
            ->where('username', $username)
            ->value('user_id');

        $query = GithubRepository::where('user_id', $userId)
            ->where('full_name', 'like', $username . '/%');

        if (!$isOwner) {
            $query->where('is_private', false);
        }

        $repositories = $query->get();

        if ($repositories->isEmpty()) {
            $synced = $this->syncRepositories($username, $userId);

            if ($synced) {
                $query = GithubRepository::where('user_id', $userId)
                    ->where('full_name', 'like', $username . '/%');

                if (!$isOwner) {
                    $query->where('is_private', false);
                }

                $repositories = $query->get();
            }
        }

        return view('dashboard', compact('repositories', 'username', 'isOwner'));
    }
}
