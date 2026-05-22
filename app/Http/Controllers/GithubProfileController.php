<?php

namespace App\Http\Controllers;

use App\Models\GithubProfile;
use App\Models\GithubRepository;
use App\Models\RepositoryLanguages;
use App\Models\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function showPublicProfile($username)
    {
        session(['active_search_username' => $username]);
        $profileInfo = GithubProfile::where('username', $username)->first();
        $isOwner = auth()->check() && (
                auth()->user()->githubProvider?->username === $username ||
                UserProvider::where('user_id', auth()->id())->where('provider', 'github')->where('username', $username)->exists()
            );
        $userId = $isOwner ? auth()->id() : UserProvider::where('provider', 'github')->where('username', $username)->value('user_id');
        $isOld = $profileInfo && $profileInfo->updated_at->toDateTimeString() < now()->subDay()->toDateTimeString();

        $hasNoPrivateDataSynced = false;
        if ($isOwner) {
            $hasNoPrivateDataSynced = !GithubRepository::where('user_id', $userId)
                ->where('is_private', true)
                ->exists();
        }

        if (!$profileInfo || $isOld || $hasNoPrivateDataSynced) {

            if (!$profileInfo || $isOld) {
                $response = Http::withHeaders(['Accept' => 'application/vnd.github+json'])
                    ->withToken(config('services.github.token'))
                    ->get("https://api.github.com/users/{$username}");

                if ($response->successful()) {
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
                            'company' => $githubData['company'] ?? null,
                            'blog' => $githubData['blog'] ?? null,
                            'twitter_username' => $githubData['twitter_username'] ?? null,
                            'hireable' => $githubData['hireable'] ?? false,
                        ]
                    );
                } elseif ($profileInfo) {
                    $profileInfo->touch();
                } else {
                    return redirect()->route('home')->with('error', 'Usuario no encontrado.');
                }

            } else {
                $profileInfo->touch();
            }
            $activityController = new GithubActivityController();
            $activityController->syncGithubActivity($username);

            $repositoriesController = new GithubRepositoryController();
            $repositoriesController->syncRepositories($username, $userId);
        }

        $privateReposCount = 0;

        if ($isOwner) {
            $privateReposCount = GithubRepository::where('user_id', auth()->id())
                ->where('is_private', true)
                ->count();
        }

        $languagesQuery = RepositoryLanguages::query()
            ->join('github_repositories', 'repository_languages.github_repository_id', '=', 'github_repositories.id')
            ->where('github_repositories.user_id', $userId)
            ->where('github_repositories.full_name', 'like', $username . '/%');

        if (!$isOwner) {
            $languagesQuery->where('github_repositories.is_private', false);
        }

        $totalBytes = (int)$languagesQuery->sum('repository_languages.bytes');

        $totalLanguagesCount = $languagesQuery->distinct('repository_languages.name')->count('repository_languages.name');

        $topLanguage = RepositoryLanguages::query()
            ->join('github_repositories', 'repository_languages.github_repository_id', '=', 'github_repositories.id')
            ->select('repository_languages.name', DB::raw('SUM(repository_languages.bytes) as total'))
            ->where('github_repositories.user_id', $userId)
            ->where('github_repositories.full_name', 'like', $username . '/%');

        if (!$isOwner) {
            $topLanguage->where('github_repositories.is_private', false);
        }

        $topLanguage = $topLanguage->groupBy('repository_languages.name')
            ->orderBy('total', 'desc')
            ->first()?->name;

        $specialtyRole = match ($topLanguage) {
            'Java', 'PHP', 'Spring Boot', 'Laravel', 'C', 'C++' => 'Especialista en Backend',
            'Dart', 'Flutter', 'Kotlin', 'Swift' => 'Desarrollador Móvil',
            'JavaScript', 'TypeScript', 'Blade', 'HTML', 'CSS' => 'Desarrollador Frontend / Fullstack',
            'Python', 'R' => 'Data Scientist / AI Engineer',
            default => 'Desarrollador de Software'
        };

        return view('profile', compact(
            'profileInfo',
            'username',
            'isOwner',
            'privateReposCount',
            'totalBytes',
            'totalLanguagesCount',
            'specialtyRole'
        ));
    }
}
