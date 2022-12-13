<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskAssignmentsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('tasks', TasksController::class);

    Route::prefix('task-assignments')->name('task-assignments.')->group(function () {
        Route::post('/assign', [TaskAssignmentsController::class, 'assign'])->name('assign');
        Route::get('/own-assignments', [TaskAssignmentsController::class, 'ownAssignments'])
            ->name('own-assignments');
        Route::patch('/approve', [TaskAssignmentsController::class, 'approve'])->name('approve');
    });

    Route::get('users', [UsersController::class, 'index'])->name('users.index');
});
