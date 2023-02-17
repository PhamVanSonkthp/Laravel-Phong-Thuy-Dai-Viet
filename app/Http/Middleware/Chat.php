<?php

namespace App\Http\Middleware;
use Closure;

class Chat
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
        if (auth()->user()->is_admin == 1) return $next($request);

        $role = optional(auth()->user())->rolePackage;
        if (empty($role)){
            return response()->json(['error' => 'Bạn không có quyền sử dụng chức năng này'],405);
        }

        $permissions = $role->permissions;
        if ($permissions->contains('key_code', config('package_services.access.chat-list'))) {
            return $next($request);
        }

        return response()->json(['error' => 'Bạn không có quyền sử dụng chức năng này'],405);
    }
}
