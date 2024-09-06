<?php

namespace App\Http\Middleware;

use App\Services\PortalAccessInfoService;
use Closure;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PortalAccessInfoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $service = new PortalAccessInfoService();
        if(Auth::user() && Auth::user()->isAdmin){
            return $next($request);
        }
        $service->registerAccess($request->ip(),$request->path(),new DateTime());
        return $next($request);
    }
}
