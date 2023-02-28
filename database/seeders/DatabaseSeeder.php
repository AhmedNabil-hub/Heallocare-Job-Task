<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Database\Seeders\EmployeeSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\EmployeePositionSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $this->call([
      EmployeeSeeder::class,
      DepartmentSeeder::class,
      // PositionSeeder::class,
      EmployeePositionSeeder::class,
      PermissionSeeder::class,
    ]);

    $admin = Employee::create([
      'name' => 'admin',
      'email' => 'admin@admin.com',
      'password' => Hash::make('password'),
    ]);

    $admin->assignRole('admin');
  }
}
