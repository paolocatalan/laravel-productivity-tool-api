<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\Subtask;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->state(['role' => 'Manager'])->has(
            Task::factory()->count(8)
        )->create();

        User::factory()->count(3)->state(['role' => 'User'])->has(
            Subtask::factory()->count(10)
        )->create();
    }
}
