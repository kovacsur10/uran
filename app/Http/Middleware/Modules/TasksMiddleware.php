<?php

namespace App\Http\Middleware\Modules;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Classes\Layout\Modules;

class TasksMiddleware{
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
        if(!$modules->isActivatedByName('tasks')){
            return redirect('/');
        }
		
        return $next($request);
    }
}
