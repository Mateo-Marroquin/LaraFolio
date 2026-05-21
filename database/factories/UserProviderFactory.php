<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserProviderFactory extends Factory
{
    protected $model = UserProvider::class;

    public function definition(): array
    {
        return [
            'provider' => $this->faker->word(),
            'provider_id' => $this->faker->word(),
            'token' => Str::random(10),
            'username' => $this->faker->userName(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
