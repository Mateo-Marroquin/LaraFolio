<?php

namespace Database\Factories;

use App\Models\GithubRepository;
use App\Models\RepositoryLanguages;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RepositoryLanguagesFactory extends Factory
{
    protected $model = RepositoryLanguages::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'bytes' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'github_repository_id' => GithubRepository::factory(),
        ];
    }
}
