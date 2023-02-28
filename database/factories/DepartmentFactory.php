<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
  public function definition(): array
  {
    $departments = [
      'human resources',
      'it',
    ];

    return [
      'name' => $this->faker->unique->randomElement($departments),
    ];
  }
}
