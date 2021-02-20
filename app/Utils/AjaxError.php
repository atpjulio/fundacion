<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Model;

class AjaxError extends Model
{
  protected $fillable = [
    'code',
    'title',
    'detail',
    'source',
    'links'
  ];

  /**
   * Methods
   */

  protected function setCode($code = 'E0000', $detail = '')
  {
    return new AjaxError([
      'code'   => $code,
      'title'  => config('ajax.errors.' . $code),
      'detail' => $detail,
    ]);
  }
}
