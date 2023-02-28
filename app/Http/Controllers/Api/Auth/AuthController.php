<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Employee;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\Employee\EmployeeLoginRequest;
use App\Http\Requests\Api\Employee\EmployeeRegisterRequest;

class AuthController extends Controller
{
  public function register(EmployeeRegisterRequest $request)
  {
    try {
      $validator = Validator::make(
        $request->all(),
        $request->rules(),
        $request->messages(),
        $request->attributes()
      );

      if ($validator->fails()) {
        return response()->json(
          [
            'errors' => $validator->getMessageBag()->all()
          ],
          Response::HTTP_NOT_ACCEPTABLE,
        );
      }

      $validated_data = $validator->validated();

      $validated_data['password'] = Hash::make($validated_data['password']);

      $employee = Employee::create($validated_data);

      $employee->assignRole('employee');

      return response()->json(
        [
          'messages' => ['You have registered successfully!']
        ],
        Response::HTTP_CREATED,
      );
    } catch (\Throwable $th) {
      return response()->json(
        [
          'errors' => [$th->getMessage()]
        ],
        Response::HTTP_INTERNAL_SERVER_ERROR,
      );
    }
  }

  public function login(EmployeeLoginRequest $request)
  {
    try {
      $validator = Validator::make(
        $request->all(),
        $request->rules(),
        $request->messages(),
        $request->attributes()
      );

      if ($validator->fails()) {
        return response()->json(
          [
            'errors' => $validator->getMessageBag()->all()
          ],
          Response::HTTP_NOT_ACCEPTABLE,
        );
      }

      $validated_data = $validator->validated();

      $employee = Employee::query()
        ->where('email', '=', $validated_data['email'])
        ?->first();

      if (!$employee || !Hash::check($validated_data['password'], $employee->password)) {
        return response()->json(
          [
            'errors' => ['Your cridentials are not correct']
          ],
          Response::HTTP_NOT_ACCEPTABLE,
        );
      }

      $employee->update(['last_login_at' => now()]);

      $token = $employee->createToken('email_login')->plainTextToken;

      return response()->json(
        [
          'token' => $token,
          'id' => $employee->id,
        ],
        Response::HTTP_OK,
      );
    } catch (\Throwable $th) {
      return response()->json(
        [
          'errors' => [$th->getMessage()]
        ],
        Response::HTTP_INTERNAL_SERVER_ERROR,
      );
    }
  }

  public function logout()
  {
    try {
      request()->user()->currentAccessToken()->delete();

      return response()->json(
        [
          'messages' => ['You logged out successfully!']
        ],
      );
    } catch (\Throwable $th) {
      return response()->json(
        [
          'errors' => [$th->getMessage()]
        ],
        Response::HTTP_INTERNAL_SERVER_ERROR,
      );
    }
  }
}
