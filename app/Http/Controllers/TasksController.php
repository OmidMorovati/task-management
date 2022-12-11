<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Contracts\ApiController;
use App\Http\Requests\Task\CreateRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\TaskResource;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class TasksController extends ApiController
{
    public function __construct(private TaskRepositoryInterface $taskRepository)
    {
    }

    public function store(CreateRequest $request): JsonResponse
    {
        $data = $this->taskRepository->store($request->validated());
        return $this->respondItemCreated(TaskResource::make($data));
    }

    public function index(): JsonResponse
    {
        $data = $this->taskRepository->all();
        return $this->respondSuccess(TaskResource::collection($data));
    }

    public function show(int $taskId): JsonResponse
    {
        $data = $this->taskRepository->find($taskId);
        return match (isset($data)) {
            true => $this->respondSuccess(TaskResource::make($data)),
            false => $this->respondInvalidParams(Lang::get('response.model_not_found'))
        };
    }

    public function update(int $taskId, UpdateRequest $request): JsonResponse
    {
        $data = $this->taskRepository->update($request->validated(), $taskId);
        return $this->respondSuccess(TaskResource::make($data));
    }

    public function destroy(int $taskId): JsonResponse
    {
        $this->taskRepository->delete($taskId);
        return $this->respondSuccess();
    }
}
