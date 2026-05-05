<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectTechnology;
use App\Models\Technology;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProjectTechnologyFactory extends Factory
{
    protected $model = ProjectTechnology::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'project_id' => Project::factory(),
            'technology_id' => Technology::factory(),
        ];
    }
}
