<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeePositionSeeder extends Seeder
{
  public function run(): void
  {
    $employees = Employee::all();
    $positions = Position::all();

    foreach ($employees as $employee) {
      foreach ($positions->random(1) as $position) {
        DB::insert(
          'insert into employees_positions (employee_id, position_id) values (?, ?)',
          [$employee->id, $position->id]
        );
      }
    }
  }
}
