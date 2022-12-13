<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Enums\TaskStatuses;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskAssignmentTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function assignor_can_assign_a_task_to_someone_else()
    {
        $assignor = User::factory()->create();
        $assignee = User::factory()->create();
        $task     = Task::factory()->create(['assignor_id' => $assignor->id]);
        $data     = [
            'assignee_email' => $assignee->email,
            'task_id'        => $task->id
        ];
        $this->actingAs($assignor)->post(route('task-assignments.assign'), $data)
            ->assertCreated()
            ->assertJsonPath('message.task.title', $task['title'])
            ->assertJsonPath('message.assignor.name', $assignor['name']);
        $this->assertDatabaseHas(Task::class, ['id' => $task->id, 'status' => TaskStatuses::ASSIGNED]);
    }

    /**
     * @test
     */
    public function assignee_can_assign_a_task_to_someone_else()
    {
        $assignor = User::factory()->create();
        $assignee = User::factory()->create();
        $task     = Task::factory()->create(['assignor_id' => $assignor->id]);
        Assignment::factory()->create(['task_id' => $task->id, 'assignee_id' => $assignee->id]);
        $data = [
            'assignee_email' => $assignor->email,
            'task_id'        => $task->id
        ];
        $this->actingAs($assignee)->post(route('task-assignments.assign'), $data)
            ->assertCreated()
            ->assertJsonPath('message.task.title', $task['title'])
            ->assertJsonPath('message.assignor.name', $assignee['name']);
        $this->assertDatabaseHas(Task::class, ['id' => $task->id, 'status' => TaskStatuses::ASSIGNED]);
    }

    /**
     * @test
     */
    public function unaffiliated_user_can_not_assign_a_task_to_someone_else()
    {
        $assignor = User::factory()->create();
        $assignee = User::factory()->create();
        $task     = Task::factory()->create(['assignor_id' => $assignor->id]);
        $data     = [
            'assignee_email' => $assignee->email,
            'task_id'        => $task->id
        ];
        $this->actingAs(User::factory()->create())->post(route('task-assignments.assign'), $data)
            ->assertSessionHasErrors(['task_id']);
    }

    /**
     * @test
     */
    public function user_can_view_own_assignments()
    {
        $assignor  = User::factory()->create();
        $assignee  = User::factory()->create();
        $taskCount = $this->faker->numberBetween(10, 20);

        Task::factory()
            ->has(Assignment::factory()->state(function () use ($assignee) {
                return ['assignee_id' => $assignee->id];
            }))
            ->count($taskCount)
            ->create(['assignor_id' => $assignor->id]);

        $this->actingAs($assignee)->get(route('task-assignments.own-assignments'))
            ->assertSuccessful()
            ->assertJsonCount($taskCount, 'message')
            ->assertJsonStructure(['message' => [['task', 'assignor']]]);
    }

    /**
     * @test
     */
    public function user_can_approve_own_assignment()
    {
        $assignee = User::factory()->create();

        $task = Task::factory()
            ->has(Assignment::factory()->state(function () use ($assignee) {
                return ['assignee_id' => $assignee->id];
            }))
            ->create();

        $this->actingAs($assignee)->patch(route('task-assignments.approve'), ['task_id' => $task->id])
            ->assertSuccessful();
        $this->assertDatabaseHas(Assignment::class, ['task_id' => $task->id, 'is_approved' => true]);
    }
}
