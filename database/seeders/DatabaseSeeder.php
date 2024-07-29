<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Task;
use App\Models\Subtask;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => 1,
            'name' => 'Paolo',
            'email' => 'hello@paolocatalan.com',
            'password' => bcrypt('U9HsTpbxkbQjJVB'),
            'email_verified_at' => now(),
            'role' => 'Administrator'
        ]);
        Role::create([
            'name' => 'administrator',
            'display_name' => 'Administrator',
            'description' => 'Responsible for the upkeep, configuration, and reliable operation of the system.'
        ]);
        Role::create([
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Senior managers of the team with responsibility for adding new tasks and maintaining existing users.'
        ]);
        Role::create([
            'name' => 'user',
            'display_name' => 'User',
            'description' => ' Privileged user has access to all clinical data.'
        ]);

        User::factory()->state(['role' => 'Manager'])->has(
            Task::factory()->count(8)
        )->create();

        User::factory()->count(3)->state(['role' => 'User'])->has(
            Subtask::factory()->count(10)
        )->create();
    }
}
