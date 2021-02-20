<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MerchantController extends Controller
{ 
  public function getMerchants(Request $request)
  {
    return view('merchant.index');
  }

  public function createMerchant()
  {
    return view('merchant.create');
  }

}
