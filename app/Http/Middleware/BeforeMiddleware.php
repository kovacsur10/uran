<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Session\Session;

class BeforeMiddleware
{
	public function handle($request, Closure $next)
	{
		if(session()->has('locale')){
			\App::setLocale(session()->get('locale'));
		}else{
			session()->put('locale', \App::getLocale());
		}
		
		return $next($request);
	}
}