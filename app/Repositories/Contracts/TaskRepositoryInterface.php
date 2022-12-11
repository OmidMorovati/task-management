<?php

namespace App\Repositories\Contracts;

use App\Models\Enums\TaskStatuses;
use Illuminate\Database\Eloquent\Model;

interface TaskRepositoryInterface extends RepositoryInterface
{
    public function updateAssignor(int $taskId, ?int $assignorId = null): ?Model;

    public function changeStatus(int $taskId, TaskStatuses $status): bool;
}
