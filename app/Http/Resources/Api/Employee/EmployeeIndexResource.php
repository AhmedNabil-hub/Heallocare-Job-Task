<?php

namespace App\Http\Resources\Api\Employee;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeIndexResource extends JsonResource
{
  public function toArray($request)
  {
    return [
			'id' => $this->id,
			'name' => $this->name,
    ];
  }
}
