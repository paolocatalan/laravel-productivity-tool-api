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
            'role_id' => 1 // Administrator
        ]);
        User::factory(3)->create();
        Task::factory(8)->create();
        Subtask::factory(20)->create();

        Role::create([
            'name' => 'administrator',
            'display_name' => 'Administrator',
            'description' => 'Responsible for the upkeep, configuration, and reliable operation of the system.'
        ]);
        Role::create([
            'name' => 'task-owner',
            'display_name' => 'Task Owner',
            'description' => 'Senior managers of the team with responsibility for adding new tasks and maintaining existing users.'
        ]);
        Role::create([
            'name' => 'user',
            'display_name' => 'User',
            'description' => ' Privileged user has access to all clinical data.'
        ]);
    }
}
