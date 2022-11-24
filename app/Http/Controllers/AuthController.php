<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Contracts\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class AuthController extends ApiController
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->email, $request->password);
        if (!$data) {
            return $this->respondUnauthorized(Lang::get('response.unauthorized'));
        }

        return $this->respondSuccess($data);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->name, $request->email, $request->password);
        if (!$data) {
            return $this->respondInternalError(Lang::get('response.internal_error'));
        }
        return $this->respondItemCreated($data);
    }

    public function me(): JsonResponse
    {
        $data = $this->authService->me();
        if (!$data) {
            return $this->respondUnauthorized(Lang::get('response.unauthorized'));
        }
        return $this->respondSuccess(UserResource::make($data));
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return $this->respondSuccess(Lang::get('response.successful_logout'));
    }
}
