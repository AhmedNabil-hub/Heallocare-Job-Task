<?php

namespace App\Filters;

trait FilterEmployee
{
  public function scopeFilterEmployee($query, $filters)
  {
    if (isset($filters) && is_array($filters)) {
      foreach ($filters as $filter_key => $filter_value) {
        if ($filter_key == 'department_id' && isset($filter_value)) {
          $query = $query->whereHas('positions', function ($query) use ($filter_value) {
            $query->where('department_id', '=', $filter_value);
          });
        }
      }
    }

    return $query;
  }
}
