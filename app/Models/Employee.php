<?php

namespace App\Models;

use App\Filters\FilterEmployee;
use App\Models\EmployeeRequest;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
  use HasApiTokens, HasRoles, HasFactory, Notifiable, FilterEmployee;

  protected $guard_name = 'api';

  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  protected $hidden = [
    'password',
  ];

  public function positions()
  {
    return $this->belongsToMany(
      Position::class,
      'employees_positions',
      'employee_id',
      'position_id',
    );
  }
}
