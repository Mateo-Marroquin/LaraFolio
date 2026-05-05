<?php

namespace App\Http\Controllers;

use App\Models\ProjectTechnology;
use Illuminate\Http\Request;

class ProjectTechnologyController extends Controller
{
    public function index()
    {
        return ProjectTechnology::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => ['required', 'exists:projects'],
            'technology_id' => ['required', 'exists:technologies'],
        ]);

        return ProjectTechnology::create($data);
    }

    public function show(ProjectTechnology $projectTechnology)
    {
        return $projectTechnology;
    }

    public function update(Request $request, ProjectTechnology $projectTechnology)
    {
        $data = $request->validate([
            'project_id' => ['required', 'exists:projects'],
            'technology_id' => ['required', 'exists:technologies'],
        ]);

        $projectTechnology->update($data);

        return $projectTechnology;
    }

    public function destroy(ProjectTechnology $projectTechnology)
    {
        $projectTechnology->delete();

        return response()->json();
    }
}
