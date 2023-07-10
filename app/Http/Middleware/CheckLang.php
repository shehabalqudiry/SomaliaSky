<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CheckLang
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
        if ($request->lang) {
            if ($request->lang == 'af') {
                App::setLocale('so');
            } else {
                App::setLocale($request->lang);
            }
        }
        return $next($request);
    }
}
