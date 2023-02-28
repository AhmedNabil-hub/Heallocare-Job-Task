<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class EmployeeFactory extends Factory
{
  public function definition(): array
  {
    return [
      'name' => $this->faker->firstName(),
      'email' => $this->faker->email(),
      'password' => Hash::make('password'),
    ];
  }
}
