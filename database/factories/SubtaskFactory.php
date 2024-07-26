<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subtasks>
 */
class SubtaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => Task::all()->random()->id,
            'user_id' => User::all()->random()->id,
            'name' => $this->faker->unique()->sentence(),
            'description' => $this->faker->text(),
            'priority' => $this->faker->randomElement(['low', 'normal', 'high'])
        ];
    }
}
