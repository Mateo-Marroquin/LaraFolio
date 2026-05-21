<?php

namespace App\Http\Controllers;

use App\Models\GithubProfile;
use App\Models\GithubRepository;
use App\Models\RepositoryLanguages;
use App\Models\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GithubProfileController extends Controller
{
    public function index()
    {
        $profileInfo = GithubProfile::where('user_id', '=', auth()->user()->id)->first();
        return view('profile', compact('profileInfo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'github_id' => ['required'],
            'username' => ['required'],
            'name' => ['nullable'],
            'avatar_url' => ['nullable'],
            'bio' => ['nullable'],
            'location' => ['nullable'],
            'public_repos' => ['required', 'integer'],
            'followers' => ['required', 'integer'],
        ]);

        return GithubProfile::create($data);
    }

    public function show(GithubProfile $githubProfile)
    {
        return $githubProfile;
    }

    public function update(Request $request, GithubProfile $githubProfile)
    {
        $data = $request->validate([
            'github_id' => ['required'],
            'username' => ['required'],
            'name' => ['nullable'],
            'avatar_url' => ['nullable'],
            'bio' => ['nullable'],
            'location' => ['nullable'],
            'public_repos' => ['required', 'integer'],
            'followers' => ['required', 'integer'],
        ]);

        $githubProfile->update($data);

        return $githubProfile;
    }

    public function destroy(GithubProfile $githubProfile)
    {
        $githubProfile->delete();

        return response()->json();
    }

    public function importProfile(Request $request)
    {
        $username = $request->input('username');
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
        ])
            ->withToken(config('services.github.token'))
            ->get("https://api.github.com/users/{$username}");

        if ($response->failed()) {
            Log::error("Error al consultar usuario de GitHub: {$username}", ['status' => $response->status()]);
            return response()->json(['error' => 'No se pudo obtener el perfil de GitHub'], $response->status());
        }

        $githubData = $response->json();

        $profile = GithubProfile::updateOrCreate(
            ['user_id' => auth()->user()->id],
            [
                'github_id' => $githubData['id'],
                'username' => $githubData['login'],
                'name' => $githubData['name'] ?? null,
                'avatar_url' => $githubData['avatar_url'] ?? null,
                'bio' => $githubData['bio'] ?? null,
                'location' => $githubData['location'] ?? null,
                'public_repos' => $githubData['public_repos'] ?? 0,
                'followers' => $githubData['followers'] ?? 0,
            ]
        );

        GithubRepository::where('user_id', '=', auth()->user()->id)->delete();
        RepositoryLanguages::where('user_id', '=', auth()->user()->id)->delete();

        $repoController = new GithubRepositoryController();
        $repoController->syncRepositories($profile->username);

        $repoLanguageController = new RepositoryLanguagesController();
        $repoLanguageController->getRepositoryLanguages();
        return redirect()->back()->with('status', 'profile-updated');
    }

    public function searchPublicProfile(Request $request)
    {
        $username = trim($request->input('username'));

        if (empty($username)) {
            return redirect()->route('home');
        }

        session(['active_search_username' => $username]);

        $profileInfo = GithubProfile::where('username', $username)->first();

        $isOld = $profileInfo && $profileInfo->updated_at->toDateTimeString() < now()->subDay()->toDateTimeString();

        if (!$profileInfo || $isOld) {
            $response = Http::withHeaders([
                'Accept' => 'application/vnd.github+json',
            ])
                ->withToken(config('services.github.token'))
                ->get("https://api.github.com/users/{$username}");

            if ($response->failed()) {
                Log::error("Error público al consultar usuario de GitHub: {$username}", ['status' => $response->status()]);
                return redirect()->route('home')->with('error', 'No se pudo encontrar ese usuario en GitHub.');
            }

            $githubData = $response->json();

            $profileInfo = GithubProfile::updateOrCreate(
                ['username' => $githubData['login']],
                [
                    'github_id' => $githubData['id'],
                    'name' => $githubData['name'] ?? null,
                    'avatar_url' => $githubData['avatar_url'] ?? null,
                    'bio' => $githubData['bio'] ?? null,
                    'location' => $githubData['location'] ?? null,
                    'public_repos' => $githubData['public_repos'] ?? 0,
                    'followers' => $githubData['followers'] ?? 0,
                ]
            );
        }

        $isOwner = auth()->check() && (auth()->user()->githubProvider?->username === $username);
        $privateReposCount = 0;

        if ($isOwner) {
            $privateReposCount = GithubRepository::where('user_id', auth()->id())
                ->where('is_private', true)
                ->count();
        }

        return view('profile', compact('profileInfo', 'username', 'isOwner', 'privateReposCount'));
    }

    public function showPublicProfile($username)
    {
        session(['active_search_username' => $username]);
        $profileInfo = GithubProfile::where('username', $username)->first();
        $isOld = $profileInfo && $profileInfo->updated_at->toDateTimeString() < now()->subDay()->toDateTimeString();

        if (!$profileInfo || $isOld) {
            $response = Http::withHeaders(['Accept' => 'application/vnd.github+json'])
                ->withToken(config('services.github.token'))
                ->get("https://api.github.com/users/{$username}");

            if ($response->failed()) return redirect()->route('home')->with('error', 'Usuario no encontrado.');

            $githubData = $response->json();
            $profileInfo = GithubProfile::updateOrCreate(
                ['username' => $githubData['login']],
                [
                    'github_id' => $githubData['id'],
                    'name' => $githubData['name'] ?? null,
                    'avatar_url' => $githubData['avatar_url'] ?? null,
                    'bio' => $githubData['bio'] ?? null,
                    'location' => $githubData['location'] ?? null,
                    'public_repos' => $githubData['public_repos'] ?? 0,
                    'followers' => $githubData['followers'] ?? 0,
                    'email' => $githubData['email'] ?? null,
                ]
            );
        }

        $activityCOntroller = new GithubActivityController();
        $activityCOntroller->syncGithubActivity($username);

        $isOwner = auth()->check() && (
                auth()->user()->githubProvider?->username === $username ||
                UserProvider::where('user_id', auth()->id())->where('provider', 'github')->where('username', $username)->exists()
            );
        $privateReposCount = 0;

        if ($isOwner) {
            $privateReposCount = GithubRepository::where('user_id', auth()->id())
                ->where('is_private', true)
                ->count();
        }

        return view('profile', compact('profileInfo', 'username', 'isOwner', 'privateReposCount'));
    }
}
