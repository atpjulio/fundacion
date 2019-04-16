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

    static function sendEmails($users, $subject, $content)
    {
        $emails = [];

        foreach ($users as $user) {
            array_push($emails, $user->email);
        }
        
        if (!env("PRODUCTION")) {
            $subject = "[ PRUEBA ] ".join(",", $emails)." -> " . time() . " " . $subject;
            $emails = [ config('constants.emails.testing') ];
            $customerName = "Ambiente de Desarrollo";
        }
        
        $data['content'] = $content;
        try {
            Mail::send('emails.template', $data, function($message) use($emails, $subject)
            {
                $message->to($emails)->subject($subject);
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
    	$message = " -> Sin salida de paciente";
    	if (count($authorizations) > 0) {

    		foreach ($authorizations as $authorization) {
				$user = $authorization->patient->toArray();
    			$subject = "Salida de paciente: ".$authorization->patient->full_name;
    			$content = "Paciente: ".$authorization->patient->full_name."<br>".
                "Con ingreso: ".\Carbon\Carbon::parse($authorization->date_from)->format("d/m/Y").
                "<br>EPS: ".$authorization->eps->name."<br>Autorización: ".$authorization->code.
                "<br><br>Tiene programada su salida para el día de hoy";

                $admins = User::whereIn('email', [config('constants.emails.admin'), config('constants.emails.admin2')])
                    ->get();
    			self::sendEmails($admins, $subject, $content);    				

    			echo  $subject."\n";

    			$authorization->update([
    				"status" => config('constants.status.inactive')
    			]);
    		}
    		$message = " -> Salida de: ".count($authorizations)." paciente(s)\n";
    	} else {
            $user['email'] = config('constants.emails.testing');
            $user['first_name'] = $user['last_name'] = '';

            $subject = "Cron de Fundación ".\Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $content = "En estos momentos se ha ejecutado el cron de ".config('constants.companyInfo.longName');
            self::sendEmail($user, $subject, $content);
        }
    	echo \Carbon\Carbon::now()->format('Y-m-d').$message;
    }

    static function normalizeString($string)
    {
        $normalizeChars = array(
            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
            'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
        );

        return strtr($string, $normalizeChars);        
    }
}
