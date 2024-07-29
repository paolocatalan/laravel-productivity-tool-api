<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Auth\Access\AuthorizationException;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_task_index(): void
    {
        $user = User::factory()->state(['role' => 'Manager'])->create(); 

        $this->actingAs($user)->postJson('/api/tasks', [
            'name' => 'Automated Testing',
            'description' => 'Test the API at every level and to make sure it is prepared to be used by its end customers.',
            'priority' => 'medium'
        ]);

        $response = $this->get('/api/tasks');

        $this->assertJson($response->getContent());

        $response->assertSee([
            'name' => 'Automated Testing'
           ]);

        $response->assertOk();
    }

    public function test_task_store(): void
    {
        $user = User::factory()->state(['role' => 'Manager'])->create();

        $this->actingAs($user)->postJson('/api/tasks', [
            'name' => 'Automated Testing',
            'description' => 'Test the API at every level and to make sure it is prepared to be used by its end customers.',
            'priority' => 'medium'
        ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'Automated Testing'
        ]);
    }

    public function test_authorization_on_updating_tasks(): void
    {
        // Exceptions::fake();

        $user = User::factory()->state(['id' => 1])->create();
        $task = Task::factory()->for(User::factory()->state(['id' => 2]))->create();

        $response = $this->actingAs($user)->patch('/api/tasks/' . $task->id, [
            'priority' => 'low'
        ]);

        $response->assertStatus(403);

        // Exceptions::assertReported(AuthorizationException::class);
        // Exceptions::assertReported(function (AuthorizationException $e) {
        //     return $e->getMessage() === 'You are not authorized to make this request.';
        // });
    }

    public function test_authorization_on_deleting_tasks(): void
    {
        $user = User::factory()->state(['role' => 'Manager'])->create();
        $task = Task::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete('/api/tasks/' . $task->id);

        $response->assertStatus(200);
        $response->assertSee([
            'message' => 'Task has been deleted from the database.'
           ]);
    }

}
