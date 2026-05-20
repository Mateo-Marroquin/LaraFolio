<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MetricsController extends Controller
{
    public function index()
    {
        $languages = auth()->user()->repositoryLanguages()
            ->select('name', DB::raw('SUM(bytes) as total_bytes'))
            ->groupBy('name')
            ->orderBy('total_bytes', 'desc')
            ->get();

        $chartData = [
            'labels' => $languages->pluck('name')->toArray(),
            'data'   => $languages->pluck('total_bytes')->toArray(),
        ];

        return view('metrics', compact('chartData'));
    }

    public function showMetrics()
    {

    }
}
