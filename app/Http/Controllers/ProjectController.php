<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'title' => ['required'],
            'description' => ['required'],
            'repo_url' => ['nullable'],
            'date' => ['required', 'date'],
            'status' => ['required'],
            'images' => ['required'],
        ]);

        return Project::create($data);
    }

    public function show(Project $project)
    {
        return $project;
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'title' => ['required'],
            'description' => ['required'],
            'repo_url' => ['nullable'],
            'date' => ['required', 'date'],
            'status' => ['required'],
            'images' => ['required'],
        ]);

        $project->update($data);

        return $project;
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json();
    }
}
