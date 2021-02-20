<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AjaxResponseProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    App::bind('ajaxResponse', function () {
      return new \App\Facades\AjaxResponse;
    });
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  { }
}
