<?php

namespace App\Http\Middleware;

use Closure;

class BothMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->hasRole('admin') or auth()->user()->hasRole('user')) {
            return $next ( $request );
        } else {
            return redirect ( '/home' );
        }
    }
}
