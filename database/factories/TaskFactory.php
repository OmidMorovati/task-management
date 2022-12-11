<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'assignor_id' => User::factory(),
            'title'       => fake()->word(),
            'description' => fake()->sentence(5),
            'deadline'    => Carbon::now()->addDays(rand(1, 10))
        ];
    }
}
