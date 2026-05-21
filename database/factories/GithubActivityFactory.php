<?php

namespace Database\Factories;

use App\Models\GithubActivity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GithubActivityFactory extends Factory
{
    protected $model = GithubActivity::class;

    public function definition(): array
    {
        return [
            'github_event_id' => $this->faker->word(),
            'username' => $this->faker->userName(),
            'type' => $this->faker->word(),
            'date' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
