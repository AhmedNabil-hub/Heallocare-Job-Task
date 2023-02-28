<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
	public function run()
	{
		$permissions = [
      'admin' => [
        'department-index',
        'department-store',
        'department-show',
        'department-update',
        'department-destroy',
        'employee-index',
        'employee-store',
        'employee-show',
        'employee-update',
        'employee-destroy',
        'position-index',
        'position-store',
        'position-show',
        'position-update',
        'position-destroy',
      ],
      'manager' => [
        'department-index',
        'department-show',
        'employee-index',
        'employee-show',
        'position-index',
        'position-show',
      ],
      'employee' => [
        'department-index',
        'department-show',
        'employee-index',
        'employee-show',
        'position-index',
        'position-show',
      ],
    ];

		foreach ($permissions['admin'] as $permission) {
			$temp[] = ['name' => $permission, 'guard_name' => 'api'];
		}

		Permission::insert($temp);

		$admin_role = Role::create(['name' => 'admin', 'guard_name' => 'api']);
		$manager_role = Role::create(['name' => 'manager', 'guard_name' => 'api']);
		$employee_role = Role::create(['name' => 'employee', 'guard_name' => 'api']);

		$admin_role->syncPermissions($permissions['admin']);
		$manager_role->syncPermissions($permissions['manager']);
		$employee_role->syncPermissions($permissions['employee']);
	}
}
