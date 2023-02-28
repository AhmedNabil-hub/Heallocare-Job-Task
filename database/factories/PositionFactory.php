<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
  public function definition(): array
  {
    $positions = [
      'employee',
      'manager',
    ];

    return [
      'name' => $this->faker->randomElement($positions),
      // 'department_id' => null,
    ];
  }
}
