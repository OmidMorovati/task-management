<?php

namespace App\Repositories\Eloquent;

use App\Models\Assignment;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use App\Repositories\Contracts\EloquentBaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class AssignmentRepository extends EloquentBaseRepository implements AssignmentRepositoryInterface
{
    protected function model(): string
    {
        return Assignment::class;
    }

    public function store(array $item): Model
    {
        return $this->model->query()->updateOrCreate(
            ['task_id' => $item['task_id']], ['assignee_id' => $item['assignee_id']]
        );
    }

    public function ownAssignments(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->query()->with($relations)->where('assignee_id', Auth::id())->get($columns);
    }
}
