<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PasswordReset;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        if (!$token) {
            Session::flash("message_danger", "Enlace incorrecto para recuperar tu contraseña");
            return view('auth.passwords.email');
        }

        $row = PasswordReset::where('token', $token)
            ->first();

        if (!$row) {
            Session::flash("message_danger", "Este enlace ya fue utilizado para recuperar la contraseña");
            return view('auth.passwords.email');
        }
        return view('auth.passwords.reset', compact('token'));
    }

    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        $user = User::checkEmail($request->get('email'));
        if (!$user) {
            Session::flash("message_danger", "Email no encontrado en el sistema");
            return redirect()->back();
        }

        $user->update([
            'password' => bcrypt($request->get('password')),
        ]);

        Session::flash("message", "Contraseña restaurada exitosamente. Ya puedes ingresar a tu cuenta");
        return view ( 'auth/login' );

    }
}
