<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Contracts\ApiController;
use App\Http\Requests\Assignment\ApproveRequest;
use App\Http\Requests\Assignment\AssignRequest;
use App\Http\Resources\Task\AssignmentResource;
use App\Services\TaskAssignmentService;
use Illuminate\Http\JsonResponse;

class TaskAssignmentsController extends ApiController
{
    public function __construct(private TaskAssignmentService $taskAssignmentService)
    {
    }

    public function assign(AssignRequest $request): JsonResponse
    {
        $data = $this->taskAssignmentService->assign($request->task_id, $request->assignee_email);
        return match (isset($data)) {
            false => $this->respondInternalError(),
            true => $this->respondItemCreated(AssignmentResource::make($data))
        };
    }

    public function ownAssignments(): JsonResponse
    {
        $data =  $this->taskAssignmentService->ownAssignments();
        return $this->respondSuccess(AssignmentResource::collection($data));
    }

    public function approve(ApproveRequest $request): JsonResponse
    {
        $data = $this->taskAssignmentService->approve($request->task_id);
        return match ($data) {
            false => $this->respondInternalError(),
            true => $this->respondSuccess()
        };
    }
}
