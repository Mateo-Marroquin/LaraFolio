<?php

namespace App\Http\Controllers;

use App\Models\GithubActivity;
use App\Models\GithubProfile;
use App\Models\RepositoryLanguages;
use App\Models\GithubRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumePdfController extends Controller
{
    public function download($username)
    {
        $profileInfo = GithubProfile::where('username', $username)->firstOrFail();

        $repositories = GithubRepository::where('full_name', 'like', $username . '/%')->get();

        $languages = RepositoryLanguages::query()
            ->join('github_repositories', 'repository_languages.github_repository_id', '=', 'github_repositories.id')
            ->select('repository_languages.name', DB::raw('SUM(repository_languages.bytes) as total_bytes'))
            ->where('github_repositories.full_name', 'like', $username . '/%')
            ->groupBy('repository_languages.name')
            ->orderBy('total_bytes', 'desc')
            ->get();

        $chartConfig = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $languages->pluck('name')->toArray(),
                'datasets' => [[
                    'data' => $languages->pluck('total_bytes')->toArray(),
                    'backgroundColor' => ['#0284c7', '#10b981', '#f59e0b', '#6366f1', '#ec4899', '#71717a']
                ]]
            ],
            'options' => [
                'plugins' => [
                    'legend' => ['position' => 'bottom'],
                ]
            ]
        ];

        $quickChartUrl = "https://quickchart.io/chart?w=400&h=300&c=" . urlencode(json_encode($chartConfig));

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
            $activityLabels[] = strval($day);
            $activityValues[] = $activityData->get($currentDateString, 0);
        }

        $lineConfig = [
            'type' => 'line',
            'data' => [
                'labels' => $activityLabels,
                'datasets' => [[
                    'label' => 'Acciones',
                    'data' => $activityValues,
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                    'borderWidth' => 2,
                    'pointRadius' => 2
                ]]
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => [
                    'y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]
                ]
            ]
        ];
        $quickChartLine = "https://quickchart.io/chart?w=550&h=200&c=" . urlencode(json_encode($lineConfig));



        $data = [
            'profileInfo'   => $profileInfo,
            'repositories'  => $repositories,
            'languages'     => $languages,
            'quickChartUrl' => $quickChartUrl,
            'lineChartUrl'  => $quickChartLine,
            'username'      => $username,
            'monthName'     => $now->translatedFormat('F'),
            'date'          => now()->format('d/m/Y')
        ];

        $pdf = Pdf::loadView('pdf.resume', $data)
            ->setOption([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true
            ]);

        return $pdf->download("Resumen_GitHub_{$username}.pdf");
    }



    public function showPreview($username)
    {
        $profileInfo = GithubProfile::where('username', $username)->firstOrFail();

        $repositoriesCount = GithubRepository::where('full_name', 'like', $username . '/%')->count();

        $languagesCount = RepositoryLanguages::query()
            ->join('github_repositories', 'repository_languages.github_repository_id', '=', 'github_repositories.id')
            ->where('github_repositories.full_name', 'like', $username . '/%')
            ->distinct('repository_languages.name')
            ->count();
        $date = now()->format('d/m/Y');

        return view('resume-preview', compact('username', 'profileInfo', 'repositoriesCount', 'languagesCount'));
    }

}
