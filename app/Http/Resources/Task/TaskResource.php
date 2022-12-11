<?php

namespace App\Http\Resources\Task;

use App\Models\Enums\TaskStatuses;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        $this->loadMissing('assignor');
        return [
            'id'          => $this->id,
            'assignor'    => $this->assignor->name,
            'title'       => $this->title,
            'description' => $this->description,
            'deadline'    => Carbon::parse($this->deadline)->toDateTimeString(),
            'status'      => TaskStatuses::from($this->status)->name
        ];
    }
}
