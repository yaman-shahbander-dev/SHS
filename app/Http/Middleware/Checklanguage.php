<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use App;
//use Illuminate\Support\Facades\Auth;

class Checklanguage
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
        //dd(Auth::check());
        if(auth()->check())
        {
            $user = User::find(auth()->id());
            $lang = $user->language;
            App::setLocale($lang);
        }

        return $next($request);
    }
}
