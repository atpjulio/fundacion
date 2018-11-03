<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ModelNotFoundException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (env("PRODUCTION") == 1) {
            if ($this->shouldReport($exception)) {            
                $this->sendEmail($exception); // sends an email
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $class = get_class($exception);
        if ($class == 'Illuminate\Auth\AuthenticationException') {
            return redirect()->to("/");
        }

        if ($exception instanceof TokenMismatchException){
            return redirect()->to("/")->with('message_danger', 'Tu sesión ha expirado');
        }

        if (env("PRODUCTION") == 1) {
            if ($this->shouldReport($exception)) {
                return response()->view("errors.500", ['exception' => $exception]); 
            }
        }

        if ($class == 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException') {
            if (auth()->check()) {
                return redirect()->to("/home");                
            }
            return redirect()->to("/")->withError('Página inválida - 404');
        }
        return parent::render($request, $exception);
    }

    public function sendEmail(Exception $exception)
    {    
       try {
            $e = FlattenException::create($exception);

            $handler = new SymfonyExceptionHandler();
            
            $data['content'] = "<h1>".env('APP_URL')."</h1>".$handler->getHtml($e) ."<br>";
            $x = json_encode(\Request::all());
            $data['content'] .= "<br>Requests<br>$x";
            $x = json_encode(\session()->all());
            $data['content'] .= "<br>Sessions<br>$x";

            \Mail::send( 'emails.errors', $data, function( $message )
            {
                $message->subject('Error en Fundación -> '.date("Y-m-d H:i:s"));
                $message->from('elmilagrobq1@gmail.com', "Reporte: Fundación");
                $message->to('atpjulio@gmail.com');
            });

        } catch (Exception $ex) {
            dd($ex);
        }
   }

}
