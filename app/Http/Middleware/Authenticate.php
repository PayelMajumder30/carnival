<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

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
        // dd($request);
        // dd($request->server['parameters']);
        if (! $request->expectsJson()) {
            // return route('login');

            // return route('front.user.login');

            if($request->is('admin') || $request->is('admin/*') || !Auth::guard('admin')->check()) {
                return route('admin.login');
            }
            // return route('front.user.login');
        }
    }
}
