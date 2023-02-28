<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\DepartmentController;

Route::group(['middleware' => ['guest']], function () {
  Route::post('/register', [AuthController::class, 'register'])->name('register');
  Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

  Route::apiResource('departments', DepartmentController::class);
  Route::apiResource('positions', PositionController::class);
  Route::apiResource('employees', EmployeeController::class);

  Route::post('/employees/send-leave-request', [EmployeeController::class, 'sendLeaveRequest']);
});

Route::get('/', function () {
  return response()->json(['Welcome Home!']);
})->name('home');
