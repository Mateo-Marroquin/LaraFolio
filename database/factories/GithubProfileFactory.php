<?php

namespace Database\Factories;

use App\Models\GithubProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GithubProfileFactory extends Factory
{
    protected $model = GithubProfile::class;

    public function definition(): array
    {
        return [
            'github_id' => $this->faker->word(),
            'username' => $this->faker->userName(),
            'name' => $this->faker->name(),
            'avatar_url' => $this->faker->url(),
            'bio' => $this->faker->word(),
            'location' => $this->faker->word(),
            'public_repos' => $this->faker->randomNumber(),
            'followers' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
