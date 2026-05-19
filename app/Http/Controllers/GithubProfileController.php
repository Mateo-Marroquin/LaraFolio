<?php

namespace App\Http\Controllers;

use App\Models\GithubProfile;
use Illuminate\Http\Request;

class GithubProfileController extends Controller
{
    public function index()
    {
        return GithubProfile::all();
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
}
