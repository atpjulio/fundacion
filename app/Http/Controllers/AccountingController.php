<?php

namespace App\Http\Controllers;

use App\Eps;
use App\Invoice;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function eps()
    {
        $epss = Eps::all();

        /** TODO: Improve this code so it can be reusable */
        $rows['eps_id'] = [];
        $rows['eps_name'] = [];
        $rows['pending'] = [];
        $rows['paid'] = [];
        $rows['pending_amount'] = [];

        foreach ($epss as $eps) {
            array_push($rows['eps_id'], $eps->id);
            array_push($rows['eps_name'], $eps->alias ?: $eps->name);

            $pending = Invoice::getUnpaidInvoices($eps->id);
            if ($pending) {
                array_push($rows['pending'], count($pending));
                array_push($rows['pending_amount'], $pending->sum('total'));
            } else {
                array_push($rows['pending'], 0);
                array_push($rows['pending_amount'], 0);
            }

            $paid = Invoice::getPaidInvoices($eps->id);
            if ($paid) {
                array_push($rows['paid'], count($paid));
                // array_push($rows['paid_amount'], $paid->sum('total'));
            } else {
                array_push($rows['paid'], 0);
                // array_push($rows['paid_amount'], 0);
            }
        }

        // dd($rows);

        return view('accounting.eps', compact('rows'));
    }

    public function accountsReceivable()
    {
        return view('accounting.accounts-receivable');
    }

    public function rips()
    {
        return view('accounting.rips');
    }
}
