<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Contracts\ApiController;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UsersController extends ApiController
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function index(): JsonResponse
    {
        $data = $this->userRepository->all();
        return $this->respondSuccess(UserResource::collection($data));
    }
}
