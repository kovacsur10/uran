<?php

namespace App\Http\Middleware\Modules;

use Closure;
use Illuminate\Http\Request;
use App\Classes\Layout\Modules;

class RoomsMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next){
		$modules = new Modules();
        if(!$modules->isActivatedByName('rooms')){
            return redirect('/');
        }
		
        return $next($request);
    }
}
