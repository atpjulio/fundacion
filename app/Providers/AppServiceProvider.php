<?php

namespace App\Providers;

use App\Models\Invoices\InvoiceSerie;
use App\Models\Patients\Companion;
use App\Observers\CompanionObserver;
use App\Observers\InvoiceSerieObserver;
use App\Services\CustomValidationRules;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot(UrlGenerator $url)
  {
    Schema::defaultStringLength(191);
    if (env('APP_HTTPS')) {
      $url->forceScheme('https');
    }

    Validator::resolver(function ($translator, $data, $rules, $messages) {
      return new CustomValidationRules($translator, $data, $rules, $messages);
    });

    InvoiceSerie::observe(InvoiceSerieObserver::class);
    Companion::observe(CompanionObserver::class);
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
