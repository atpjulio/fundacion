<?php

namespace App\Http\Controllers;

use App\City;
use App\EpsService;
use App\Invoice;
use App\Patient;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getDayRange($yearMonth)
    {
        $year = explode("-", $yearMonth)[0];
        $month = sprintf("%02d", explode("-", $yearMonth)[1]);

        $finalDay = \Carbon\Carbon::parse($year."-".$month."-01")->endOfMonth()->format("d");

        return view('partials._birth_day', compact('finalDay'));
    }

    public function getServices($id)
    {
        $services = EpsService::getServices($id);
        if (count($services) < 1) {
            $services = [
                "0" => 'Sin servicios registrados'
            ];
        }
        return view('partials._services', compact('services'));
    }

    public function getCompanionServices($id)
    {
        $companionServices = EpsService::getServices($id)->pluck('name', 'id');
        if (count($companionServices) < 1) {
/*
            $companionServices = [
                "0" => 'Sin servicios registrados'
            ];
*/            
        }
        return view('partials._companion_services', compact('companionServices'));
    }

    public function getCities($stateCode)
    {
        $cities = City::getCitiesByStateId($stateCode);

        return view('partials._cities', compact('cities'));
    }


    public function getEpsPatients($id)
    {
        $patients = Patient::getPatientsForEps($id);
        return view('partials._eps_patients', compact('patients'));
    }

    public function getInvoicesAmount($data) 
    {
        $epsId = explode("_", $data)[0];
        $initialDate = explode("_", $data)[1];
        $finalDate = explode("_", $data)[2];

        $invoicesAmount = count(Invoice::getInvoicesByEpsId($epsId, $initialDate, $finalDate));
        return view('partials._invoice_amount', compact('invoicesAmount'));

    }
}
