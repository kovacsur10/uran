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

class PasswordController extends Controller{
	
	public function showResetForm(){
        return view('auth.password.reset', ["layout" => new LayoutData()]);
    }
	
	public function reset(Request $request){
		$this->validate($request, [
            'username' => 'required',
		]);
		
		$user = DB::table('users')->where('username', 'LIKE', $request->input('username'))
								  ->first();
		if($user == null){ 
			$layout = new LayoutData();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_reseting_password'),
										 "url" => '/password/reset']);
		}
		$day = Carbon::now()->dayOfYear;
		$string = sha1($request->input('username').$user->registration_date.$user->name.$day);
		if(Session::has('lang')){
			if(Session::get('lang') == "hu_HU" || Session::get('lang') == "en_US")
				$lang = Session::get('lang');
			else
				$lang = "hu_HU";
		}else{
			$lang = "hu_HU";
		}
		Mail::send('mails.resetpwd'.$lang, ['name' => $user->name, 'link' => 'http://host59.collegist.eotvos.elte.hu/password/reset/'.$user->username.'/'.$string], function ($m) use ($user) {
            $m->to($user->email, $user->name);
			$m->subject('Elfelejtett jelszó');
        });
		
		$layout = new LayoutData();
		return view('success.success', ["layout" => $layout,
										"message" => $layout->language('success_send_email_about_what_to_do'),
										"url" => '/']);
	}
	
	public function showPasswordForm($username, $code){
		$user = DB::table('users')->where('username', 'LIKE', $username)
								  ->first();
		if($user == null){ 
			$layout = new LayoutData();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_reseting_password'),
										 "url" => '/password/reset']);
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
			return view('auth.password.email', ["layout" => new LayoutData(),
											    "username" => $username]);
		}else{
			$layout = new LayoutData();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_reseting_password'),
										 "url" => '/password/reset']);
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
			
		return view('success.success', ["layout" => $layout,
										"message" => $layout->language('success_at_reset_password'),
										"url" => '/']);
	}
}
