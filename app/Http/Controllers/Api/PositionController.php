<?php

namespace App\Http\Controllers\Api;

use App\Models\Position;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\Position\StorePositionRequest;
use App\Http\Requests\Api\Position\UpdatePositionRequest;

class PositionController extends Controller
{
  public function __construct()
  {
    $this->middleware(
      'role_or_permission:admin|manager|employee|index-position',
      ['only' => ['index']]
    );
    $this->middleware(
      'role_or_permission:admin|store-position',
      ['only' => ['store']]
    );
    $this->middleware(
      'role_or_permission:admin|manager|employee|show-position',
      ['only' => ['show']]
    );
    $this->middleware(
      'role_or_permission:admin|update-position',
      ['only' => ['update']]
    );
    $this->middleware(
      'role_or_permission:admin|destroy-position',
      ['only' => ['destroy']]
    );
  }

  public function index()
  {
    try {
      $positions = Position::all();

      return response()->json(
        [
          'data' => $positions,
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

  public function store(StorePositionRequest $request)
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

      Position::create($validated_data);

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

  public function show(Position $position)
  {
    try {
      return response()->json(
        [
          'data' => $position,
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

  public function update(UpdatePositionRequest $request, Position $position)
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

      $position->update($validated_data);

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

  public function destroy(Position $position)
  {
    try {
      $position->delete();

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
