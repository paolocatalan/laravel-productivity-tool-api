<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\v1\Project;
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
            'password' => bcrypt('strongPassword'),
            'email_verified_at' => now(),
            'role' => 'Administrator'
        ]);

        User::factory()->count(2)->state(['role' => 'Manager'])->create();
        User::factory()->count(5)->state(['role' => 'User'])->create();
        
        $this->call([
            RoleSeeder::class,
            ProjectSeeder::class,
            TaskSeeder::class
        ]);


    }
}
