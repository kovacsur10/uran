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
		$this->validateLogin($request);
		$user = DB::table('users')->where('username', 'LIKE', $request->input('username'))
								  ->first();
		if($user != null){ 
			if(password_verify($request->input('password'), $user->password)){
				DB::table('users')
					->where('username', 'LIKE', $user->username)
					->update(['last_online' => Carbon::now()->toDateTimeString()]);
				Session::put('user', $user);
				return view('home', ["layout" => new LayoutData()]);
			}else{ //password doesn't match
				return view('auth.login.error', ["layout" => new LayoutData()]);
			}
		}else{ //username not found
			return view('auth.login.error', ["layout" => new LayoutData()]);
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
	
	public function showRegistrationForm(){
        return view('auth.register', ["layout" => new LayoutData()]);
    }
	
	public function register(Request $request){
		$regTime = Carbon::now();
        $string = sha1($request->input('username') . $regTime->toDateTimeString() . $request->input('email'));
        $this->validate($request, [
            'username' => 'required|min:6|max:32|unique:users|unique:registrations|regex:/(^[A-Za-z0-9_\-]+$)/',
            'email' => 'required|email|max:255|unique:users|unique:users|unique:registrations',
            'password' => 'required|min:8|max:64|confirmed||regex:/(^[A-Za-z0-9\-_\/]+$)/',
            'name' => 'required',
			'country' => 'required',
			'shire' => 'required',
			'postalcode' => 'required',
			'address' => 'required',
			'city' => 'required',
			'reason' => 'required',
		]);
		DB::table('registrations')->insert([
			'username' => $request->input('username'),
            'password' => password_hash($request->input('password'), PASSWORD_DEFAULT),
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'code' => $string,
            'registration_date' => $regTime->toDateTimeString(),
			'country' => $request->input('country'),
			'shire' => $request->input('shire'),
			'postalcode' => $request->input('postalcode'),
			'address' => $request->input('address'),
			'city' => $request->input('city'),
			'reason' => $request->input('reason'),
			'phone' => $request->input('phone'),
		]);
		Mail::send('mails.verification', ['name' => $request->input('name'), 'link' => 'http://host59.collegist.eotvos.elte.hu/register/'.$string], function ($m) use ($request) {
            $m->to($request->input('email'), $request->input('name'));
			$m->subject('Regisztráció megerősítése');
        });
		
		return view('auth.register.ok', ["layout" => new LayoutData()]);
    }
	
	public function vefify($code){
		$user = DB::table('registrations')->where('code', 'LIKE', $code)->get();
		if($user != null){
			$regTime = Carbon::now();
			DB::table('registrations')
				->where('code', $code)
				->update(['verified' => 1,
						  'verification_date' => $regTime]);
			
			return view('auth.verification.ok', ["layout" => new LayoutData()]);
		}else{
			return view('auth.verification.error', ["layout" => new LayoutData()]);
		}
	}
}
