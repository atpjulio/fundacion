<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Puc;

class PucController extends Controller
{
    public function index()
    {
        $pucs = Puc::orderBy('code')->get();

        return $pucs;
    }
}
