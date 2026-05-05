<?php

namespace Database\Factories;

use App\Models\Technology;
use App\Models\User;
use App\Models\UserTechnology;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserTechnologyFactory extends Factory
{
    protected $model = UserTechnology::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'technology_id' => Technology::factory(),
        ];
    }
}
