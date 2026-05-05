<?php

namespace App\Http\Controllers;

use App\Models\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    public function index()
    {
        return Technology::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'category' => ['required'],
        ]);

        return Technology::create($data);
    }

    public function show(Technology $technology)
    {
        return $technology;
    }

    public function update(Request $request, Technology $technology)
    {
        $data = $request->validate([
            'name' => ['required'],
            'category' => ['required'],
        ]);

        $technology->update($data);

        return $technology;
    }

    public function destroy(Technology $technology)
    {
        $technology->delete();

        return response()->json();
    }
}
