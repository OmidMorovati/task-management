<?php

namespace App\Http\Resources\Task;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    public function toArray($request)
    {
        $this->loadMissing('task.assignor');
        return [
            'id'          => $this->id,
            'task'        => TaskResource::make($this->task),
            'assignor'    => UserResource::make($this->task->assignor),
            'is_approved' => (bool)$this->is_approved
        ];
    }
}
