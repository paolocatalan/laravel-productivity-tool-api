<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\v1\Project;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    public function test_project_index_returning_correct_structure(): void
    {
        Project::factory()->create();
        $response = $this->getJson('/api/v1/projects');
        $response
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'attributes' => [
                        'title',
                        'description',
                        'objective',
                        'start_date',
                        'end_date'
                    ],
                    'relationships' => [
                        'id',
                        'manager',
                        'manager email'
                    ]
                ]
            ]
        ]);
    }

    public function test_project_store(): void
    {
        $user = User::factory()->state(['role' => 'Manager'])->create();

        $response = $this->actingAs($user)->postJson('/api/v1/projects', [
            'title' => 'Email Marketing',
            'description' => 'Promotional messages or newsletters to a list of subscribers via email',
            'objective'=> 'building customer relationships, promoting services, increasing awareness and sales.',
            'start_date' => '2024-10-10 10:10:10',
            'end_date' => '2024-10-20 10:10:10'
        ]);

        $response->assertStatus(201);
    }

    public function test_project_store_validation(): void
    {
        $user = User::factory()->state(['role' => 'Manager'])->create();

        $response = $this->actingAs($user)->postJson('/api/v1/projects', [
            'title' => 'Email Marketing',
            'description' => 'Promotional messages or newsletters to a list of subscribers via email',
            'objective'=> 'building customer relationships, promoting services, increasing awareness and sales.',
            'start_date' => '2024-10-10 10:10:10',
            'end_date' => '2024-10-10'
        ]);

        $response->assertStatus(422);
        $response->assertSee([
            'message' => 'The end date field must match the format Y-m-d H:s:i'
           ]);
    }

    public function test_project_store_authorization(): void
    {
        $user = User::factory()->state(['role' => 'Manager', 'approved' => 0])->create();

        $response = $this->actingAs($user)->postJson('/api/v1/projects', [
            'title' => 'Email Marketing',
        ]);

        $response->assertStatus(401);
        $response->assertSee([
            'message' => 'Your account is waiting for approval.'
           ]);
    }

    public function test_project_destroy(): void
    {
        $user = User::factory()->state(['role' => 'Manager'])->create();
        $project = Project::factory()->for($user)->create();

        $response = $this->actingAs($user)->deleteJson('/api/v1/projects/' . $project->id);

        $response->assertStatus(200);
        $response->assertSee([
            'message' => 'Task has been deleted from the database.'
           ]);

    }

}
