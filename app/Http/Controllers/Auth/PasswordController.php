<?php

namespace App\Http\Controllers\Auth;

use App\Classes\User;
use App\Classes\Auth;
use App\Classes\LayoutData;
use App\Classes\Layout\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail;

class PasswordController extends Controller{
	
// PUBLIC FUNCTIONS
	
	public function showResetForm(){
        return view('auth.password.reset', ["layout" => new LayoutData()]);
    }
	
	public function reset(Request $request){
		$this->validate($request, [
            'username' => 'required',
		]);
		$layout = new LayoutData();
		$user = User::getUserDataByUsername($request->input('username'));
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
		Mail::send('mails.resetpwd_'.$lang, ['name' => $user->name, 'link' => url('/password/reset/'.$user->username.'/'.$string)], function ($m) use ($user, $layout) {
            $m->to($user->email, $user->name);
			$m->subject($layout->language('forgotten_password'));
        });
		return view('success.success', ["layout" => $layout,
										"message" => $layout->language('success_send_email_about_what_to_do'),
										"url" => '/']);
	}
	
	public function showPasswordForm($username, $code){
		$user = User::getUserDataByUsername($username);
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
		$layout = new LayoutData();
		//validation part
		$this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:8|max:64|confirmed||regex:/(^[A-Za-z0-9\-_\/]+$)/',
		]);
		
		Auth::updatePassword($request->input('username'), $request->input('password'));
			
		return view('success.success', ["layout" => $layout,
										"message" => $layout->language('success_at_reset_password'),
										"url" => '/']);
	}
}
