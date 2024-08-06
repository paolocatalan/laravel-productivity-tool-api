<?php

namespace Database\Factories\v1;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence(),
            'description' => $this->faker->text(),
            'objective' => $this->faker->text(),
            'start_date' => $this->faker->dateTimeBetween('+1 Day', '+30 Days'),
            'end_date' => $this->faker->dateTimeBetween('+30 Day', '+60 Days'),
            'user_id' => User::factory() //User::where('role', 'Manager')->inRandomOrder()->first()->id,
        ];
    }
}
