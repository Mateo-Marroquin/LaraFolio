<?php

namespace Database\Factories;

use App\Models\Technology;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TechnologyFactory extends Factory
{
    protected $model = Technology::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'category' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
