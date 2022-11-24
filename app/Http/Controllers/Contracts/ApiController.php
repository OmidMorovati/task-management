<?php

namespace App\Http\Controllers\Contracts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    protected int $statusCode = Response::HTTP_OK;

    protected function respondSuccess(mixed $message = null): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_OK)->respondWithSuccess($message);
    }

    protected function respondNotFound(mixed $message = null): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message);
    }

    protected function respondUnauthorized(mixed $message = null): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)->respondWithError($message);
    }

    protected function respondInvalidParams(mixed $message = null): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    protected function respondInternalError(mixed $message = null): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    protected function respondItemCreated(mixed $message = null): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->respondWithSuccess($message);
    }

    private function getStatusCode(): int
    {
        return $this->statusCode;
    }

    private function setStatusCode(int $statusCode): ApiController
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    private function respondWithError(mixed $message): JsonResponse
    {
        $message ??= Lang::get('response.operation_failed');
        return $this->respond([
            'success' => false,
            'message' => $message
        ]);
    }

    private function respondWithSuccess(mixed $message = null): JsonResponse
    {
        $message ??= Lang::get('response.operation_successful');
        return $this->respond([
            'success' => true,
            'message' => $message
        ]);
    }

    private function respond($data, array $headers = []): JsonResponse
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

}
