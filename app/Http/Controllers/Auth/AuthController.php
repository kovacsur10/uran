<?php

namespace App\Http\Controllers\Auth;

use App\Classes\User;
use App\Classes\LayoutData;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Mail;

class AuthController extends Controller{	
    public function showLoginForm(){
        return view('auth.login', ["layout" => new LayoutData()]);
    }
	
	public function login(Request $request){
		$request->merge(array('username' => strtolower($request->input('username'))));
		$this->validateLogin($request);
		$user = DB::table('users')->where('username', 'LIKE', $request->input('username'))
			->where('registered', '=', 1)
			->first();
		if($user != null){ 
			if(password_verify($request->input('password'), $user->password)){
				DB::table('users')
					->where('username', 'LIKE', $user->username)
					->update(['last_online' => Carbon::now()->toDateTimeString()]);
				Session::put('user', $user);
				return view('home', ["layout" => new LayoutData()]);
			}else{ //password doesn't match
				$layout = new LayoutData();
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('unsuccessful_login'),
											 "url" => '/login']);
			}
		}else{ //username not found
			$layout = new LayoutData();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('unsuccessful_login'),
										 "url" => '/login']);
		}
	}
	
	public function logout(){
		Session::forget('user');
		return view('auth.login', ["layout" => new LayoutData()]);
	}
	
    public function validateLogin(Request $request){
        $this->validate($request, [
            'username' => 'required',
			'password' => 'required',
        ]);
    }
	
}
