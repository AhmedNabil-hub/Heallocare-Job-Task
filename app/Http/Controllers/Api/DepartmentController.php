<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\Department\StoreDepartmentRequest;
use App\Http\Requests\Api\Department\UpdateDepartmentRequest;

class DepartmentController extends Controller
{
  public function __construct()
  {
    $this->middleware(
      'role_or_permission:admin|manager|employee|index-department',
      ['only' => ['index']]
    );
    $this->middleware(
      'role_or_permission:admin|store-department',
      ['only' => ['store']]
    );
    $this->middleware(
      'role_or_permission:admin|manager|employee|show-department',
      ['only' => ['show']]
    );
    $this->middleware(
      'role_or_permission:admin|update-department',
      ['only' => ['update']]
    );
    $this->middleware(
      'role_or_permission:admin|destroy-department',
      ['only' => ['destroy']]
    );
  }

  public function index()
  {
    try {
      $departments = Department::all();

      return response()->json(
        [
          'data' => $departments,
        ],
        Response::HTTP_OK,
      );
    } catch (\Throwable $th) {
      return response()->json(
        [
          'errors' => [$th->getMessage()],
        ],
        Response::HTTP_INTERNAL_SERVER_ERROR,
      );
    }
  }

  public function store(StoreDepartmentRequest $request)
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
            'errors' => $validator->getMessageBag()->all(),
          ],
          Response::HTTP_NOT_ACCEPTABLE,
        );
      }

      $validated_data = $validator->validated();

      Department::create($validated_data);

      return response()->json(
        [
          'messages' => ['data created successfully!'],
        ],
        Response::HTTP_CREATED,
      );
    } catch (\Throwable $th) {
      return response()->json(
        [
          'errors' => [$th->getMessage()],
        ],
        Response::HTTP_INTERNAL_SERVER_ERROR,
      );
    }
  }

  public function show(Department $department)
  {
    try {
      return response()->json(
        [
          'data' => $department,
        ],
        Response::HTTP_OK,
      );
    } catch (\Throwable $th) {
      return response()->json(
        [
          'errors' => [$th->getMessage()],
        ],
        Response::HTTP_INTERNAL_SERVER_ERROR,
      );
    }
  }

  public function update(UpdateDepartmentRequest $request, Department $department)
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
            'errors' => $validator->getMessageBag()->all(),
          ],
          Response::HTTP_NOT_ACCEPTABLE,
        );
      }

      $validated_data = $validator->validated();

      $department->update($validated_data);

      return response()->json(
        [
          'messages' => ['data updated successfully!'],
        ],
        Response::HTTP_CREATED,
      );
    } catch (\Throwable $th) {
      return response()->json(
        [
          'errors' => [$th->getMessage()],
        ],
        Response::HTTP_INTERNAL_SERVER_ERROR,
      );
    }
  }

  public function destroy(Department $department)
  {
    try {
      $department->delete();

      return response()->json(
        [
          'messages' => ['data deleted successfully!'],
        ],
        Response::HTTP_OK,
      );
    } catch (\Throwable $th) {
      return response()->json(
        [
          'errors' => [$th->getMessage()],
        ],
        Response::HTTP_INTERNAL_SERVER_ERROR,
      );
    }
  }
}
