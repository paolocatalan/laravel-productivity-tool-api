<?php

namespace Database\Factories;

use App\Enums\TaskPriorityEnums;
use App\Enums\TaskStagesEnums;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\v1\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::where('role', 'Manager')->inRandomOrder()->first()->id,
            'project_id' => Project::all()->random()->id,
            'name' => $this->faker->unique()->sentence(),
            'description' => $this->faker->text(),
            'due_date' => $this->faker->dateTimeBetween('+1 days', '+30 days'),
            'priority' => $this->faker->randomElement(array_column(TaskPriorityEnums::cases(), 'value')),
            'stage' => $this->faker->randomElement(array_column(TaskStagesEnums::cases(), 'value'))
        ];
    }
}
