<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as BaseEncrypter;
use Auth;
use Closure;
use Request;
use Cookie;
use Crypt;

class ClientLogged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {

            return $next($request);
        }

        return redirect('/login')
            ->withCookie(cookie('login_path', $request->getRequestUri()));
    }

}
