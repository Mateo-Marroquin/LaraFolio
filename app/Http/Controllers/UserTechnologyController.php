<?php

namespace App\Http\Controllers;

use App\Models\UserTechnology;
use Illuminate\Http\Request;

class UserTechnologyController extends Controller
{
    public function index()
    {
        return UserTechnology::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'technology_id' => ['required', 'exists:technologies'],
        ]);

        return UserTechnology::create($data);
    }

    public function show(UserTechnology $userTechnology)
    {
        return $userTechnology;
    }

    public function update(Request $request, UserTechnology $userTechnology)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'technology_id' => ['required', 'exists:technologies'],
        ]);

        $userTechnology->update($data);

        return $userTechnology;
    }

    public function destroy(UserTechnology $userTechnology)
    {
        $userTechnology->delete();

        return response()->json();
    }
}
