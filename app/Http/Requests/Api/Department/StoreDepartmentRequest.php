<?php

namespace App\Http\Requests\Api\Department;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormRequestPreventAutoValidation;

class StoreDepartmentRequest extends FormRequest
{
  use FormRequestPreventAutoValidation;

  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'name' => 'required|string|min:3',
    ];
  }

  public function messages()
  {
    return [
      'required'   => ':attribute is required',
      'string'     => ':attribute must be a string',
      'integer'   => ':attribute must be an integer',
      'unique'     => 'This :attribute already exists',
      'exists'     => 'This :attribute does not exists',
      'between'   => 'This :attribute must be between :min and :max',
      'min'        => 'This :attribute must be more than :min',
      'regex'      => 'This :attribute is not correct',
      'email'      => 'This :attribute must be a valid email',
    ];
  }

  public function attributes()
  {
    return [];
  }
}
