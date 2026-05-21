<?php

namespace App\Http\Controllers;

use App\Models\GithubProfile;
use App\Models\RepositoryLanguages;
use Illuminate\Support\Facades\DB;

class MetricsController extends Controller
{
    public function index($username = null)
    {
        if (!$username && auth()->check()) {
            $username = auth()->user()->githubProvider->username ?? auth()->user()->name;
        }

        if (!$username) {
            return redirect()->route('welcome');
        }

        $profileInfo = GithubProfile::where('username', $username)->first();

        $languages = RepositoryLanguages::query()
            ->join('github_repositories', 'repository_languages.github_repository_id', '=', 'github_repositories.id')
            ->select('repository_languages.name', DB::raw('SUM(repository_languages.bytes) as total_bytes'))
            ->where('github_repositories.full_name', 'like', $username . '/%')
            ->groupBy('repository_languages.name')
            ->orderBy('total_bytes', 'desc')
            ->get();

        $chartData = [
            'labels' => $languages->pluck('name')->toArray(),
            'data'   => $languages->pluck('total_bytes')->toArray(),
        ];

        return view('metrics', compact('chartData', 'username', 'profileInfo'));
    }

    public function showMetrics()
    {

    }
}
