<?php

namespace Tests\Unit;

use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class SubtaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_subtask_index_json_structure(): void
    {
        $user = User::factory()->state(['role' => 'User'])->create();
        $task = Task::factory()->has(Subtask::factory()->for($user))->create();
        $response = $this->actingAs($user)->getJson('/api/v1/tasks/' . $task->id .' /subtasks');

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

    public function test_subtask_store_authorization(): void
    {
        $user = User::factory()->state(['name' => 'John Carmack', 'role' => 'User'])->create();
        $task = Task::factory()->has(Subtask::factory()->for(User::factory()->state(['name' => 'Jim Keller'])))->create();

        $response = $this->actingAs($user)->postJson('/api/v1/tasks/' . $task->id . '/subtasks', [
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

    public function test_subtask_store_assignee_validation(): void
    {
        $user = User::factory()->state(['name' => 'John Carmack', 'role' => 'Manager'])->create();
        $task = Task::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/tasks/' . $task->id . '/subtasks', [
            'assignee' => 'Jim Keller',
        ]);

        $response->assertStatus(422);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['message', 'errors'])
        );
    }

    public function test_subtask_updating_successfully(): void
    {
        $user = User::factory()->state(['id' => 1, 'role' => 'User'])->create();
        $task = Task::factory()->has(Subtask::factory()->state(['id' => 1, 'user_id' => 1]))->create();

        $response = $this->actingAs($user)->patchJson('/api/v1/tasks/' . $task->id . '/subtasks/1', [
            'priority' => 'Low'
        ]);

        $response->assertStatus(200);
    }

    public function test_subtasks_destroy_authorization(): void
    {
        $user = User::factory()->state(['id' => 1, 'role' => 'User'])->create(); 
        $task = Task::factory()->has(Subtask::factory()->state(['id' => 1, 'user_id' => 1]))->create();

        $response = $this->actingAs($user)->delete('/api/tasks/' . $task->id . '/subtasks/1');

        $response->assertStatus(403);
    }
}
