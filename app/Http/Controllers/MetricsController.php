<?php

namespace App\Http\Controllers;

use App\Models\GithubActivity;
use App\Models\GithubProfile;
use App\Models\RepositoryLanguages;
use Illuminate\Support\Carbon;
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


        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth = $now->copy()->endOfMonth()->toDateString();
        $daysInMonth = $now->daysInMonth;

        $activityData = GithubActivity::where('username', $username)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select(DB::raw('DATE(date) as clean_date'), DB::raw('count(*) as total'))
            ->groupBy('clean_date')
            ->get()
            ->pluck('total', 'clean_date');

        $activityLabels = [];
        $activityValues = [];

        $year = $now->year;
        $month = sprintf('%02d', $now->month);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dayString = sprintf('%02d', $day);
            $currentDateString = "{$year}-{$month}-{$dayString}";

            $activityLabels[] = "Día " . $day;

            $activityValues[] = $activityData->get($currentDateString, 0);
        }

        $timelineChartData = [
            'labels' => $activityLabels,
            'data'   => $activityValues,
            'monthName' => $now->translatedFormat('F')
        ];
        //dd($timelineChartData);

        return view('metrics', compact('chartData', 'timelineChartData', 'username', 'profileInfo'));
    }

    public function showMetrics()
    {

    }
}
