<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Utilities extends Model
{
    static function sendEmail($user, $subject, $content)
    {
        $customerEmail = $user['email'];
        $customerName = $user['first_name'] . " " . $user['last_name'];
        
        if (!env("PRODUCTION")) {
            $subject = "Prueba -> " . $customerEmail . " -> " . time() . " " . $subject;
            $customerEmail = config('constants.emails.testing');
            $customerName = "Ambiente de Desarrollo";
        }
        
        $data['users'] = $user;
        $data['content'] = $content;
        try {
            Mail::send('emails.template', $data, function($message) use($customerEmail, $customerName, $subject)
            {
                $message->to($customerEmail, $customerName)->subject($subject);
                $message->from(config('constants.companyInfo.email'), config('constants.companyInfo.longName'));
            });
        } catch (Exception $e) {
            dd($e);
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    static function emailDepartures()
    {
    	$authorizations = Authorization::findDepartures();
    	$consoleMessage = " -> Sin salida de paciente";

    	if ($authorizations) {

    		foreach ($authorizations as $authorization) {
				$user = $authorization->patient->toArray();
    			$subject = "Salida de paciente: ".$authorization->patient->full_name;
    			$content = "Paciente: ".$authorization->patient->full_name."<br>Con ingreso: ".
    				\Carbon\Carbon::parse($authorization->date_from)->format("d/m/Y")."<br>EPS: ".
    				$authorization->eps->name."<br>Autorización: ".$authorization->code."<br><br>Tiene programada su salida para el día de hoy";

				$user['email'] = config('constants.emails.admin');
    			self::sendEmail($user, $subject, $content);
				$user['email'] = config('constants.emails.admin2');
    			self::sendEmail($user, $subject, $content);    				

    			echo  $subject."\n";

    			$authorization->update([
    				"status" => config('constants.status.inactive')
    			]);
    		}
    		$message = " -> Salida de: ".count($authorizations)." paciente(s)\n";
    	}
    	echo \Carbon\Carbon::now()->format('Y-m-d').$message;
    }
}
