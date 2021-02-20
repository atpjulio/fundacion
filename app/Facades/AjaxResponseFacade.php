<?php

namespace app\Facades;

use Illuminate\Support\Facades\Facade;

class AjaxResponseFacade extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'ajaxResponse';
  }
}
