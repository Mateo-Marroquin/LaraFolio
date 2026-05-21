<?php

namespace App\Http\Controllers;

use App\Models\GithubProfile;
use App\Models\RepositoryLanguages;
use App\Models\GithubRepository;
use Illuminate\Http\Request;
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

        $data = [
            'profileInfo'   => $profileInfo,
            'repositories'  => $repositories,
            'languages'     => $languages,
            'quickChartUrl' => $quickChartUrl,
            'username'      => $username,
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

        return view('resume-preview', compact('username', 'profileInfo', 'repositoriesCount', 'languagesCount'));
    }

}
