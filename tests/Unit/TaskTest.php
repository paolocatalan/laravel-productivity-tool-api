<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Models\v1\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_task_index(): void
    {
        $user = User::factory()->state(['role' => 'Manager'])->create(); 
        Project::factory()->state(['title' => 'Digital Marketing'])->create();

        $this->actingAs($user)->postJson('/api/v1/tasks', [
            'project' => 'Digital Marketing',
            'name' => 'Automated Testing',
            'description' => 'Test the API at every level and to make sure it is prepared to be used by its end customers.',
            'due_date' => '2024-08-11 11:00:11',
            'priority' => 'Normal',
            'stage' => 'Not started'
        ]);

        $response = $this->get('/api/v1/tasks');

        $this->assertJson($response->getContent());

        $response->assertSee([
            'name' => 'Automated Testing'
           ]);

        $response->assertOk();
    }

    public function test_task_store(): void
    {
        $user = User::factory()->state(['role' => 'Manager'])->create();
        Project::factory()->state(['title' => 'Digital Marketing'])->create();

        $this->actingAs($user)->postJson('/api/v1/tasks',
            [
                'project' => 'Digital Marketing',
                'name' => 'Automated Testing',
                'description' => 'Test the API at every level and to make sure it is prepared to be used by its end customers.',
                'due_date' => '2024-08-11 11:00:11',
                'priority' => 'High',
                'stage' => 'Not started'
            ]
        );

        $this->assertDatabaseHas('tasks', [
            'name' => 'Automated Testing'
        ]);
    }

    public function test_authorization_on_updating_tasks(): void
    {
        $user = User::factory()->state(['role' => 'User'])->create();
        $task = Task::factory()->for(User::factory())->create();

        $response = $this->actingAs($user)->patchJson('/api/v1/tasks/' . $task->id, [
            'priority' => 'Low'
        ]);

        $response->assertStatus(403);
    }

    public function test_authorization_on_deleting_tasks(): void
    {
        $user = User::factory()->state(['role' => 'Manager'])->create();
        $task = Task::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete('/api/v1/tasks/' . $task->id);

        $response->assertStatus(200);
        $response->assertSee([
            'message' => 'Task has been deleted from the database.'
           ]);
    }

}
