<?php

namespace Database\Factories;

use App\Models\GithubRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GithubRepositoryFactory extends Factory
{
    protected $model = GithubRepository::class;

    public function definition(): array
    {
        return [
            'github_repo_id' => $this->faker->word(),
            'name' => $this->faker->name(),
            'full_name' => $this->faker->name(),
            'html_url' => $this->faker->url(),
            'description' => $this->faker->text(),
            'primary_languaje' => $this->faker->word(),
            'stars_count' => $this->faker->randomNumber(),
            'forks_count' => $this->faker->randomNumber(),
            'is_fork' => $this->faker->boolean(),
            'github_updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
