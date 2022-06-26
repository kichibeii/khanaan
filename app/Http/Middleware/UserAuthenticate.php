<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserAuthenticate
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
        if(Auth::check()) {
            $user = Auth::user();
            if ($user->status <> 1) {
                Auth::logout();
                if ($user->hasRole('member')){
                    return redirect('login');
                } else {
                    return redirect('admin/login');
                }
            }
        }
        return $next($request);
    }
}
