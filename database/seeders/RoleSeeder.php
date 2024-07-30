<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
