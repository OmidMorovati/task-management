<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_view_all_users()
    {
        $usersCount = $this->faker->numberBetween(10, 50);
        $users      = User::factory()->count($usersCount)->create();
        $this->actingAs($users->first())->get(route('users.index'))
            ->assertSuccessful()
            ->assertJsonCount($usersCount, 'message')
            ->assertJsonStructure(['success', 'message' => [['name', 'email']]]);
    }
}
