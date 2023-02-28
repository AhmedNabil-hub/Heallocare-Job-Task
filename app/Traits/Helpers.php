<?php

use App\Models\Mediafile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\MediafileController;

function getResourceResponse(Request $request, $model, $data_type, $data)
{
  $type = ucfirst(str_contains($request->route()->getPrefix() ?? '', 'api') ? 'api' : 'web');
  $model = ucfirst($model);
  if (!in_array($data_type, ['Collection', 'IndexResource', 'ShowResource'])) {
    return false;
  }

  $path = "App\\Http\\Resources\\$type\\$model\\";

  $obj_name = $path . $model . $data_type;

  return json_decode(json_encode(new $obj_name($data)));
}

function generatePermissionsNames()
{
  $loader = require base_path('vendor/autoload.php');
  $admin = [];
  $main = [];

  foreach ($loader->getClassMap() as $class => $file) {
    if (
      preg_match('/[a-z]+Controller$/', $class) &&
      !in_array($class, ['HomeController', 'AdminController', 'MainController'])
    ) {
      $reflection = new ReflectionClass($class);
      // $methods = [];
      $class_name = explode('\\', $class);

      // exclude inherited methods
      foreach ($reflection->getMethods() as $method) {
        if ($method->class == $reflection->getName() && $method->name != '__construct') {
          if (in_array('Admin', $class_name)) {
            $admin[] = strtolower(str_replace('Controller', '', end($class_name))) . '-' . $method->name;
          } elseif (in_array('Main', $class_name)) {
            $main[] = strtolower(str_replace('Controller', '', end($class_name))) . '-' . $method->name;
          }
        }
      }
    }
  }

  return [
    'admin' => $admin,
    'main' => $main,
  ];
}
