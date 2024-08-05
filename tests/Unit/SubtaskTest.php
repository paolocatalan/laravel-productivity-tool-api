<?php

namespace Tests\Unit;

use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use App\Models\v1\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class SubtaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_subtask_index(): void
    {
        $this->seed();
        $user = User::factory()->state(['role' => 'User'])->create();

        $response = $this->actingAs($user)->getJson('/api/tasks/1/subtasks');

        $response
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'attributes' => [
                            'name',
                            'description',
                            'priority',
                            'created_at',
                            'updated_at'
                        ],
                        'relationships' => [
                            'id',
                            'user name',
                            'user email'
                        ]
                    ]
                ]
            ]);
    }

    public function test_subtask_store(): void
    {
        $this->seed();
        $user = User::factory()->state(['name' => 'Jim Keller', 'role' => 'User'])->create();

        $response = $this->actingAs($user)->postJson('/api/tasks/1/subtasks', [
            'assignee' => 'Jim Keller',
            'name' => 'Automated Testing',
            'description' => 'Test the API at every level and to make sure it is prepared to be used by its end customers.',
            'priority' => 'normal'
        ]);

        $response->assertStatus(403);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['message', 'error'])
        );
    }

    public function test_subtask_update(): void
    {
        $this->seed();
        $user = User::factory()->state(['id' => 99, 'role' => 'User'])->create();
        $task = Task::factory()->has(Subtask::factory()->state(['id' => 99, 'user_id' => 99]))->create();

        $response = $this->actingAs($user)->patchJson('/api/tasks/' . $task->id . '/subtasks/99', [
            'priority' => 'low'
        ]);

        $response->assertStatus(200);
    }

    public function test_authorization_on_deleting_subtasks(): void
    {
        $user = User::factory()->state(['id' => 1, 'role' => 'User'])->create(); 
        $task = Task::factory()->for(User::factory()->state(['id' => 2]))->has(Subtask::factory()->state(['id' => 1]))->create();

        $response = $this->actingAs($user)->delete('/api/tasks/' . $task->id . '/subtasks/1');

        $response->assertStatus(403);
    }
}
