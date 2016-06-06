<?php

namespace App\Http\Controllers\Auth;

use App\Classes\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Mail;

class PasswordController extends Controller{
	
	public function showResetForm(){
        return view('auth.password.reset', ["logged" => Session::has('user'),
											"user" => null]);
    }
	
	public function reset(Request $request){
		$this->validate($request, [
            'username' => 'required',
		]);
		
		$user = DB::table('users')->where('username', 'LIKE', $request->input('username'))
								  ->first();
		if($user == null){ 
			return view('auth.password.error', ["logged" => Session::has('user'),
												"user" => null]);
		}
		$day = Carbon::now()->dayOfYear;
		$string = sha1($request->input('username').$user->registration_date.$user->name.$day);
		Mail::send('mails.resetpwd', ['name' => $user->name, 'link' => 'http://host59.collegist.eotvos.elte.hu/password/reset/'.$user->username.'/'.$string], function ($m) use ($user) {
            $m->to($user->email, $user->name);
			$m->subject('Elfelejtett jelszó');
        });
		
		return view('auth.password.sent', ["logged" => Session::has('user'),
										   "user" => null]);
	}
	
	public function showPasswordForm($username, $code){
		$user = DB::table('users')->where('username', 'LIKE', $username)
								  ->first();
		if($user == null){ 
			return view('auth.password.error', ["logged" => Session::has('user'),
												"user" => null]);
		}
		$day = Carbon::now()->dayOfYear;
		$i = 0;
		while($i < 5 && sha1($username.$user->registration_date.$user->name.$day) != $code){
			$day--;
			if($day < 0)
				$day = 365;
			$i++;
		}
		if($i < 5){
			return view('auth.password.email', ["logged" => Session::has('user'),
											    "username" => $username,
												"user" => null]);
		}else{
			return view('auth.password.error', ["logged" => Session::has('user'),
												"user" => null]);
		}
    }
	
	public function completeReset(Request $request){
		$this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:8|max:64|confirmed||regex:/(^[A-Za-z0-9\-_\/]+$)/',
		]);
		
		DB::table('users')
            ->where('username', 'LIKE', $request->input('username'))
            ->update(array('password' => password_hash($request->input('password'), PASSWORD_DEFAULT)));
			
		return view('auth.password.ok', ["logged" => Session::has('user'),
										 "user" => null]);
	}
}
