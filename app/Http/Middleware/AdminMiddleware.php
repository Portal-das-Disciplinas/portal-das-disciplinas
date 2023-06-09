<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Middleware que verifica se um usuário é administrador.
     *
     * @param  \Illuminate\Http\Request  $request Objeto contendo as informações da requisição.
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if(Auth::user() && Auth::user()->isAdmin){
            return $next($request);
        }

        return redirect('login');
    }
}
