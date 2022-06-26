<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $urlParts = explode('.', $_SERVER['HTTP_HOST']);
        $subdomain = $urlParts[0];

        if (! $request->expectsJson()) {
            return count($urlParts) == 3 ? route($subdomain.'.login') : route('register');
        }
    }
}
