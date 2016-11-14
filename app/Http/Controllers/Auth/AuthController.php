<?php

namespace App\Http\Controllers\Auth;

use App\Classes\LayoutData;
use App\Classes\Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller{	
    public function showLoginForm(){
        return view('auth.login', ["layout" => new LayoutData()]);
    }
	
	public function login(Request $request){
		$this->validateLoginData($request);
		
		try{
			Auth::login($request->username, $request->password);
			return view('home', ["layout" => new LayoutData()]);
		}catch(\Exception $ex){
			$layout = new LayoutData();
			return view('errors.error', ["layout" => $layout,
					"message" => $layout->language('unsuccessful_login'),
					"url" => '/login']);
		}
	}
	
	public function logout(){
		Auth::logout();
		return view('auth.login', ["layout" => new LayoutData()]);
	}
	
    public function validateLoginData(Request $request){
        $this->validate($request, [
            'username' => 'required',
			'password' => 'required',
        ]);
    }
	
}
