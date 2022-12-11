<?php

namespace App\Repositories\Eloquent;

use App\Models\Enums\TaskStatuses;
use App\Models\Task;
use App\Repositories\Contracts\EloquentBaseRepository;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class TaskRepository extends EloquentBaseRepository implements TaskRepositoryInterface
{
    protected function model(): string
    {
        return Task::class;
    }

    public function store(array $item): Model
    {
        $item['assignor_id'] = Auth::id();
        return parent::store($item)->load('assignor');
    }

    public function update(array $item, int $id = null): Model
    {
        $model = $this->find($id);
        throw_if(!isset($model), new ModelNotFoundException(Lang::get('response.model_not_found')));
        if ($model->assignor_id !== Auth::id() || $model->status === TaskStatuses::ASSIGNED->value) {
            abort(Response::HTTP_FORBIDDEN, Lang::get('you can not update this model'));
        }
        $model->update($item);
        return $model;
    }

    public function delete(int $id = null): int
    {
        $model = $this->find($id);
        throw_if(!isset($model), new ModelNotFoundException(Lang::get('response.model_not_found')));
        if ($model->assignor_id !== Auth::id() || $model->status === TaskStatuses::ASSIGNED->value) {
            abort(Response::HTTP_FORBIDDEN, Lang::get('you can not delete this model'));
        }
        return $this->model::destroy($id);
    }

    public function updateAssignor(int $taskId, ?int $assignorId = null): ?Model
    {
        $assignorId ??= Auth::id();
        $model = $this->find($taskId);
        if ($assignorId != $model->assignor_id){
            $model->update(['assignor_id' => $assignorId]);
        }
        return $model->fresh();
    }

    public function changeStatus(int $taskId, TaskStatuses $status): bool
    {
       return (bool)$this->model->query()->where('id', $taskId)->update(['status' => $status->value]);
    }
}
