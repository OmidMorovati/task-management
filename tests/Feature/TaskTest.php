<?php

namespace Tests\Feature;

use App\Models\Enums\TaskStatuses;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_create_a_task()
    {
        $user = User::factory()->create();
        $data = Task::factory()->make()->toArray();
        $this->actingAs($user)->post(route('tasks.store'), $data)
            ->assertCreated()
            ->assertJsonPath('message.title', $data['title'])
            ->assertJsonStructure(['success', 'message' => ['title', 'description', 'deadline']]);
    }

    /**
     * @test
     */
    public function user_can_view_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $this->actingAs($user)->get(route('tasks.show', ['task' => $task->id]))
            ->assertSuccessful()
            ->assertJsonPath('message.title', $task['title'])
            ->assertJsonStructure(['success', 'message' => ['title', 'description', 'deadline']]);
    }

    /**
     * @test
     */
    public function user_can_view_all_task()
    {
        $user      = User::factory()->create();
        $taskCount = $this->faker->numberBetween(10, 20);
        Task::factory()->count($taskCount)->create();
        $this->actingAs($user)->get(route('tasks.index'))
            ->assertSuccessful()
            ->assertJsonCount($taskCount, 'message')
            ->assertJsonStructure(['success', 'message' => [['title', 'description', 'deadline']]]);
    }

    /**
     * @test
     */
    public function user_can_update_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['assignor_id' => $user->id]);
        $data = [
            'title'       => $this->faker->word(),
            'description' => $this->faker->sentence(5),
        ];
        $this->actingAs($user)->put(route('tasks.update', array_merge(['task' => $task->id], $data)))
            ->assertSuccessful()
            ->assertJsonPath('message.title', $data['title'])
            ->assertJsonPath('message.description', $data['description'])
            ->assertJsonStructure(['success', 'message' => ['title', 'description', 'deadline']]);
    }

    /**
     * @test
     */
    public function user_can_not_update_assigned_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['assignor_id' => $user->id, 'status' => TaskStatuses::ASSIGNED]);
        $data = [
            'title'       => $this->faker->word(),
            'description' => $this->faker->sentence(5),
        ];
        $this->actingAs($user)->put(route('tasks.update', array_merge(['task' => $task->id], $data)))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function user_can_not_update_someone_else_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $data = [
            'title'       => $this->faker->word(),
            'description' => $this->faker->sentence(5),
        ];
        $this->actingAs($user)->put(route('tasks.update', array_merge(['task' => $task->id], $data)))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function user_can_delete_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['assignor_id' => $user->id]);

        $this->actingAs($user)->delete(route('tasks.destroy', ['task' => $task->id]))
            ->assertSuccessful();
        $this->assertDatabaseMissing(Task::class, ['id' => $task->id]);
    }


    /**
     * @test
     */
    public function user_can_not_delete_someone_else_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $this->actingAs($user)->delete(route('tasks.destroy', ['task' => $task->id]))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function user_can_not_delete_assigned_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['assignor_id' => $user->id, 'status' => TaskStatuses::ASSIGNED]);

        $this->actingAs($user)->delete(route('tasks.destroy', ['task' => $task->id]))
            ->assertForbidden();
    }
}
