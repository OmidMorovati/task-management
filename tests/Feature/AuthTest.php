<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_register()
    {
        $user = User::factory()->make();
        $data = [
            'name'     => $user->name,
            'email'    => $user->email,
            'password' => $user->password
        ];
        $this->post(route('register'), $data)
            ->assertCreated()
            ->assertJsonStructure(['success', 'message' => ['access_token']]);
    }

    /**
     * @test
     */
    public function user_can_login()
    {
        $password = Str::random();
        $user     = User::factory()->create(['password' => Hash::make($password)]);
        $data     = [
            'email'    => $user->email,
            'password' => $password
        ];
        $this->post(route('login'), $data)
            ->assertSuccessful()
            ->assertJsonStructure(['success', 'message' => ['access_token']]);
    }

    /**
     * @test
     */
    public function user_can_see_profile()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get(route('me'))
            ->assertSuccessful()
            ->assertJsonPath('message.name', $user->name)
            ->assertJsonPath('message.email', $user->email);
    }

    /**
     * @test
     */
    public function user_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $this->post(route('logout'))->assertSuccessful();
    }
}
