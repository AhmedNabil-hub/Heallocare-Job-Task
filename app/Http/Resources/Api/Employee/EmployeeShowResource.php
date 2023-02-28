<?php

namespace App\Http\Resources\Api\Employee;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeShowResource extends JsonResource
{
	public function toArray($request)
	{
    $positions = [];
    foreach ($this->positions ?? [] as $position) {
      $positions[$position->id] = [
        'name' => $position->name,
        'department_id' => $position->department?->id,
        'department_name' => $position->department?->name,
      ];
    }

    return [
      'id' => $this->id,
      'name' => $this->name,
      'positions' => $positions,
    ];
	}
}
