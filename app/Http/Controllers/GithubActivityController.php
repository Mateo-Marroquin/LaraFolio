<?php

namespace App\Http\Controllers;

use App\Models\GithubActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GithubActivityController extends Controller
{
    public function index()
    {
        return GithubActivity::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'github_event_id' => ['required'],
            'username' => ['required'],
            'type' => ['required'],
            'date' => ['required', 'date'],
        ]);

        return GithubActivity::create($data);
    }

    public function show(GithubActivity $githubActivity)
    {
        return $githubActivity;
    }

    public function update(Request $request, GithubActivity $githubActivity)
    {
        $data = $request->validate([
            'github_event_id' => ['required'],
            'username' => ['required'],
            'type' => ['required'],
            'date' => ['required', 'date'],
        ]);

        $githubActivity->update($data);

        return $githubActivity;
    }

    public function destroy(GithubActivity $githubActivity)
    {
        $githubActivity->delete();

        return response()->json();
    }

    public function syncGithubActivity($username)
    {
        try {
            $response = Http::withHeaders(['Accept' => 'application/vnd.github+json',])
                ->withToken(config('services.github.token'))
                ->get("https://api.github.com/users/{$username}/events");

            if ($response->successful()) {
                $events = $response->json();

                foreach ($events as $event) {
                    $eventDate = Carbon::parse($event['created_at'])->toDateString();

                    GithubActivity::updateOrCreate(
                        ['github_event_id' => $event['id']],
                        [
                            'username' => $username,
                            'type'     => $event['type'],
                            'date'     => $eventDate
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error("Error sincronizando actividad de GitHub para {$username}: " . $e->getMessage());
        }
    }
}
