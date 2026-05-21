<?php

namespace App\Http\Controllers;

use App\Models\UserProvider;
use Illuminate\Http\Request;

class UserProviderController extends Controller
{
    public function index()
    {
        return UserProvider::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'provider' => ['required'],
            'provider_id' => ['required'],
            'token' => ['required'],
            'username' => ['required'],
        ]);

        return UserProvider::create($data);
    }

    public function show(UserProvider $userProvider)
    {
        return $userProvider;
    }

    public function update(Request $request, UserProvider $userProvider)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'provider' => ['required'],
            'provider_id' => ['required'],
            'token' => ['required'],
            'username' => ['required'],
        ]);

        $userProvider->update($data);

        return $userProvider;
    }

    public function destroy(UserProvider $userProvider)
    {
        $userProvider->delete();

        return response()->json();
    }
}
