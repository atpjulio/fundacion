<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PasswordReset;
use App\User;
use App\Utilities;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $user = User::checkEmail($request->get('email'));
        if (!$user) {
            Session::flash('message_danger', 'Email no registrado en el sistema');
            return redirect()->back();
        }

        $token = str_random(20);

        PasswordReset::storeRecord($request->get('email'), $token);

        $url = env('APP_URL')."/password/reset/$token";
        $subject = "Recuperación de Contraseña";
        $content = "Hola ".$user->full_name.",<br><br>"
            ."Este correo ha sido enviado automáticamente como petición de renovar la contraseña en ".env('APP_URL')."<br><br>"
            ."Por favor haz clic en el siguiente enlace para que puedas ingresar una nueva contraseña y recuperes el acceso al sistema<br><br>"
            .$url;

        Utilities::sendEmail($user->toArray(), $subject, $content);

        Session::flash("message", "Se ha enviado un correo a ".$request->get('email')." con los detalles de la recuperación de tu contraseña");
        return redirect()->back();
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

}
