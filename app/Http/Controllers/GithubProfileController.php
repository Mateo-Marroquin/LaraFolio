<?php

namespace App\Http\Controllers;

use App\Models\GithubProfile;
use App\Models\GithubRepository;
use App\Models\RepositoryLanguages;
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
                'username'     => $githubData['login'],
                'name'         => $githubData['name'] ?? null,
                'avatar_url'   => $githubData['avatar_url'] ?? null,
                'bio'          => $githubData['bio'] ?? null,
                'location'     => $githubData['location'] ?? null,
                'public_repos' => $githubData['public_repos'] ?? 0,
                'followers'    => $githubData['followers'] ?? 0,
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
}
