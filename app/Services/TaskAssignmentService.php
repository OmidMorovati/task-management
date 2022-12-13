<?php

namespace App\Services;

use App\Models\Enums\TaskStatuses;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

class TaskAssignmentService
{
    public function __construct(
        private TaskRepositoryInterface       $taskRepository,
        private AssignmentRepositoryInterface $assignmentRepository,
        private UserRepositoryInterface       $userRepository
    )
    {
    }

    public function assign(int $taskId, string $assigneeEmail)
    {
        $user = $this->userRepository->findByEmail($assigneeEmail, ['id']);
        try {
            $this->taskRepository->beginTransaction();
            $this->taskRepository->updateAssignor($taskId);
            $result = $this->assignmentRepository->store([
                'task_id'     => $taskId,
                'assignee_id' => $user->id
            ]);
            $this->taskRepository->changeStatus($taskId, TaskStatuses::ASSIGNED);
            $this->taskRepository->commit();
        } catch (\Throwable $e) {
            $this->taskRepository->rollBack();
            $result = null;
            Log::error(__CLASS__, [__METHOD__ => $e->getMessage()]);
        }
        return $result;
    }

    public function ownAssignments()
    {
        return $this->assignmentRepository->ownAssignments(['*'], ['task.assignor']);
    }

    public function approve(int $taskId): bool
    {
        return $this->assignmentRepository->approve($taskId);
    }
}
