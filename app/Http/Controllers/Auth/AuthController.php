<?php

namespace App\Http\Controllers\Auth;

use App\Classes\User;
use App\Classes\LayoutData;
use App\Classes\Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class AuthController extends Controller{	
    public function showLoginForm(){
        return view('auth.login', ["layout" => new LayoutData()]);
    }
	
	public function login(Request $request){
		$request->merge(array('username' => strtolower($request->input('username'))));
		$this->validateLogin($request);
		$user = Auth::getUser($request->input('username'));
		if($user != null){ 
			if(password_verify($request->input('password'), $user->password)){
				try{
					Auth::updateLoginDate($user->username);
					Auth::setUserLanguage($user->username);
				}catch(\Illuminate\Database\QueryException $e){
					return view('errors.error', ["layout" => $layout,
												 "message" => $layout->language('unsuccessful_login'),
												 "url" => '/login']);
				}
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
