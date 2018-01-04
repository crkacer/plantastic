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
        // if user didn't log in yet, save the cookie with content of entered uri
        // if yes then allow request to go through
        if (Auth::check()) {

            return $next($request);
        }
        
        return redirect('/login')
            ->withCookie(cookie('login_path', $request->getRequestUri()));
    }

}
