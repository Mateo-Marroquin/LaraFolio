<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return Profile::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'about_me' => ['required'],
            'profile_picture' => ['required'],
        ]);

        return Profile::create($data);
    }

    public function show(Profile $profile)
    {
        return $profile;
    }

    public function update(Request $request, Profile $profile)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'about_me' => ['required'],
            'profile_picture' => ['required'],
        ]);

        $profile->update($data);

        return $profile;
    }

    public function destroy(Profile $profile)
    {
        $profile->delete();

        return response()->json();
    }
}
