<?php

namespace App\Http\Controllers;

use App\Models\GithubActivity;
use App\Models\UserProvider;
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
            $token = config('services.github.token');
            $url = "https://api.github.com/users/{$username}/events";

            $userId = UserProvider::where('provider', 'github')
                ->where('username', $username)
                ->value('user_id');

            if ($userId && auth()->check() && auth()->id() == $userId) {
                $provider = auth()->user()->githubProvider ?? UserProvider::where('user_id', auth()->id())->where('provider', 'github')->first();
                if ($provider) {
                    $token = $provider->token;
                    //$url = "https://api.github.com/user/events";
                }
            }

            $response = Http::withHeaders(['Accept' => 'application/vnd.github+json',])
                ->withToken($token)
                ->get($url, [
                    'per_page' => 100
                ]);

            if ($response->successful()) {
                $events = $response->json();

                foreach ($events as $event) {
                    $eventDate = Carbon::parse($event['created_at'])->toDateString();

                    GithubActivity::updateOrCreate(
                        ['github_event_id' => $event['id']],
                        [
                            'username' => $username,
                            'type' => $event['type'],
                            'date' => $eventDate,
                            'is_private' => !$event['public']
                        ]
                    );
                }
            } else {
                Log::error("Fallo en API de eventos para {$username}. Status: " . $response->status());
            }
        } catch (\Exception $e) {
            Log::error("Error sincronizando actividad de GitHub para {$username}: " . $e->getMessage());
        }
    }
}
