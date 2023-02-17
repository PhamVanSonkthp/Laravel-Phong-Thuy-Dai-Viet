<?php

namespace App\Http\Middleware;
use Closure;

class Banned
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

        if($request->user()->user_status_id == 2){
            return response()->json(['error' => 'Tài khoản của bạn đã bị khóa'],405);
        }

        return $next($request);
    }
}
