<?php

namespace App\Repositories\Eloquent;

use App\Models\Assignment;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use App\Repositories\Contracts\EloquentBaseRepository;
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
}
