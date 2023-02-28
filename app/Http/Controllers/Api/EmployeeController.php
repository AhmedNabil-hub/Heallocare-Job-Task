<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\Employee\StoreEmployeeRequest;
use App\Http\Requests\Api\Employee\UpdateEmployeeRequest;
use App\Models\Department;
use App\Notifications\EmployeeLeaveRequestNotification;

class EmployeeController extends Controller
{
  public function __construct()
  {
    $this->middleware(
      'role_or_permission:admin|manager|employee|index-employee',
      ['only' => ['index']]
    );
    $this->middleware(
      'role_or_permission:admin|store-employee',
      ['only' => ['store']]
    );
    $this->middleware(
      'role_or_permission:admin|manager|employee|show-employee',
      ['only' => ['show']]
    );
    $this->middleware(
      'role_or_permission:admin|update-employee',
      ['only' => ['update']]
    );
    $this->middleware(
      'role_or_permission:admin|destroy-employee',
      ['only' => ['destroy']]
    );
    $this->middleware(
      'role_or_permission:employee',
      ['only' => ['sendLeaveRequest']]
    );
  }

  public function index(Request $request)
  {
    try {
      $filters = array_filter([
        'department_id' => $request->filter_department_id ?? null,
      ]);

      $employees = Employee::query()
        ->with(['positions'])
        ->when($filters != null, function ($query) use ($filters) {
          $query->filterEmployee($filters);
        })
        ->get();

      return response()->json(
        [
          'data' => $employees,
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

  public function store(StoreEmployeeRequest $request)
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

      $employee = Employee::create(Arr::except($validated_data, ['positions', 'roles']));

      if (isset($validated_data['positions'])) {
        $employee->positions()->sync($validated_data['positions']);
      }

      if (isset($validated_data['roles'])) {
        $employee->roles()->sync($validated_data['roles']);
      }

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

  public function show(Employee $employee)
  {
    try {
      $employee->load(['positions']);

      return response()->json(
        [
          'data' => $employee,
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

  public function update(UpdateEmployeeRequest $request, Employee $employee)
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

      $employee->update(Arr::except($validated_data, ['positions', 'roles']));

      if (isset($validated_data['positions'])) {
        $employee->positions()->sync($validated_data['positions']);
      }

      if (isset($validated_data['roles'])) {
        $employee->roles()->sync($validated_data['roles']);
      }

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

  public function destroy(Employee $employee)
  {
    try {
      $employee->positions()->detach();
      $employee->roles()->detach();
      $employee->delete();

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

  public function sendLeaveRequest(Request $request)
  {
    try {
      $validator = Validator::make(
        $request->all(),
        [
          'position_id' => 'required|exists:positions,id',
        ],
        [
          'required'   => ':attribute is required',
          'exists'     => 'This :attribute does not exist',
        ],
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

      $department = Department::query()
        ->whereHas('positions', function ($query) use ($validated_data) {
          $query->where([
            ['id', '=', $validated_data['position_id']]
          ]);
        })
        ?->first();

      $manager = Employee::query()
        ->whereHas('positions', function ($query) use ($department) {
          $query->where([
            ['name', '=', 'manager'],
            ['department_id', '=', $department->id]
          ]);
        })
        ?->first();

      $manager->notify(new EmployeeLeaveRequestNotification(['from' => auth()->user()->email]));

      return response()->json(
        [
          'messages' => ['email sent successfully!'],
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
}
