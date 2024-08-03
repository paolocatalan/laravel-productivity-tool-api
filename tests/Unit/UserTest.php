<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_user_registration(): void
    {
        $response = $this->postJson('/api/register', [
            'id' => '1',
            'name' => fake()->name(),
            'email' => fake()->email(),
            'role' => 'User',
            'password' => 'strongPassword',
            'password_confirmation' => 'strongPassword'
        ]);

        $response
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user',
                    'token'
                ]
            ]);
    }


}
