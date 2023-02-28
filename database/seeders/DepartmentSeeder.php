<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
  public function run(): void
  {
    Department::factory()
      ->count(2)
      ->has(
        Position::factory(1),
        'positions'
      )
      ->create();
  }
}
