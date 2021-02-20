<?php

namespace App\Http\Controllers;

use App\Facades\AjaxResponse;
use Illuminate\Http\Request;

class MerchantController extends Controller
{ 
  public function getMerchants()
  {
    return view('merchant.index');
  }

  public function createMerchant()
  {
    return view('merchant.create');
  }

  /**
   * Ajax
   */

  public function getAjaxMerchants(Request $request)
  {
    return AjaxResponse::okPaginated();
  }
}
