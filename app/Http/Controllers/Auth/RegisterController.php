<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Database;
use App\Classes\Layout\EcnetUser;
use App\Classes\LayoutData;
use App\Classes\Notifications;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail;

class RegisterController extends Controller{	
	
	public function showRegistrationChoserForm(){
        return view('auth.chooseregister', ["layout" => new LayoutData()]);
    }
	
	public function showCollegistRegistrationForm(){
        return view('auth.register.collegist', ["layout" => new LayoutData()]);
    }
	
	public function showGuestRegistrationForm(){
        return view('auth.register.guest', ["layout" => new LayoutData()]);
    }
	
	public function registerGuest(Request $request){
		$layout = new LayoutData();
		$request->merge(array('username' => strtolower($request->input('username'))));
        $string = sha1($request->input('username') . Carbon::now()->toDateTimeString() . $request->input('email'));
        $this->validate($request, [
            'username' => 'required|min:6|max:32|unique:users|regex:/(^[A-Za-z0-9_\-]+$)/',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|max:64|confirmed||regex:/(^[A-Za-z0-9\-_\/\.\?\:]+$)/',
            'name' => 'required',
			'country' => 'required',
			'shire' => 'required',
			'postalcode' => 'required',
			'address' => 'required',
			'city' => 'required',
			'reason' => 'required',
			'phone' => 'required',
			'accept' => 'required',
		]);
		
		Database::beginTransaction(); //DATABASE TRANSACTION STARTS HERE
		try{
			$layout->registrations()->insertGuestData($request->input('username'), $request->input('password'), $request->input('email'), $request->input('name'), $request->input('country'), $request->input('shire'), $request->input('postalcode'), $request->input('address'), $request->input('city'), $request->input('reason'), $request->input('phone'), $layout->lang());	
		}catch(\Illuminate\Database\QueryException $e){
		}
		$userId = $layout->registrations()->getNotVerifiedUserData($request->input('username'));
		if($userId === null){
			Database::rollback();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_sending_registration_verification_email'),
										 "url" => '/register']);
		}else{
			try{
				$layout->registrations()->addCode($userId->id, $string);
				$layout->registrations()->addUserDefaultPermissions('guest', $userId->id);
			}catch(\Illuminate\Database\QueryException $e){
				Database::rollback();
				return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_sending_registration_verification_email'),
										 "url" => '/register']);
			}
			
			// ECNET PART
			if($layout->modules()->isActivatedByName('ecnet')){
				$layout->setUser(new EcnetUser(0));
				$layout->user()->register($userId->id);
			}
			// ECNET PART END
			
			Database::commit();
			if($layout->lang() == "hu_HU" || $layout->lang() == "en_US")
				$lang = $layout->lang();
			else
				$lang = "hu_HU";
			Mail::send('mails.verification_'.$lang, ['name' => $request->input('name'), 'link' => url('/register/'.$string)], function ($m) use ($request, $layout) {
				$m->to($request->input('email'), $request->input('name'));
				$m->subject($layout->language('confirm_registration'));
			});
			Notifications::notifyAdminFromServer('accept_user_registration', $layout->language('new_user_registered'), $layout->language('new_user_registered_description'), 'admin/registration/show');
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_sending_registration_verification_email'),
											"url" => '/register']);
		}
    }
	
	public function registerCollegist(Request $request){
		$layout = new LayoutData();
		$request->merge(array('username' => strtolower($request->input('username'))));
        $string = sha1($request->input('username') . Carbon::now()->toDateTimeString() . $request->input('email'));
        $this->validate($request, [
            'username' => 'required|min:6|max:32|unique:users|regex:/(^[A-Za-z0-9_\-]+$)/',
            'email' => 'required|email|max:255|unique:users|unique:users',
            'password' => 'required|min:8|max:64|confirmed||regex:/(^[A-Za-z0-9\-_\/\.\?\:]+$)/',
            'name' => 'required',
			'country' => 'required',
			'shire' => 'required',
			'postalcode' => 'required',
			'address' => 'required',
			'city' => 'required',
			'city_of_birth' => 'required',
			'name_of_mother' => 'required',
			'phone' => 'required',
			'high_school' => 'required',
			'neptun' => 'required|min:6|max:6',
			'from_year' => 'required',
			'faculty' => 'required',
			'workshop' => 'required',
			'accept' => 'required',
		]);
		$this->validate($request, array('year_of_leaving_exam' => array('required', 'regex:/(^(?:19[6-9][0-9])|(?:200[0-9])|(?:201[0-6])$)/')));
		$this->validate($request, array('date_of_birth' => array('required', 'regex:/(^(?:19[0-9]{2}|2[0-9]{3})\.(?:1[012]|0[1-9])\.(?:0[1-9]|[12][0-9]|3[01])\.?$)/')));
		Database::beginTransaction(); //DATABASE TRANSACTION STARTS HERE
		$layout->registrations()->insertCollegistData($request->input('username'), $request->input('password'), $request->input('email'), $request->input('name'), $request->input('country'), $request->input('shire'), $request->input('postalcode'), $request->input('address'), $request->input('city'), $request->input('phone'), $layout->lang(), $request->input('city_of_birth'), $request->input('date_of_birth'), $request->input('name_of_mother'), $request->input('year_of_leaving_exam'), $request->input('high_school'), $request->input('neptun'), $request->input('from_year'), $request->input('faculty'), $request->input('workshop'));
		$userId = $layout->registrations()->getNotVerifiedUserData($request->input('username'));
		if($userId == null){
			Database::rollback();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_sending_registration_verification_email'),
										 "url" => '/register']);
		}else{
			try{
				$layout->registrations()->addCode($userId->id, $string);
				$layout->registrations()->addUserDefaultPermissions('collegist', $userId->id);
			}catch(\Illuminate\Database\QueryException $e){
				Database::rollback();
				return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_sending_registration_verification_email'),
										 "url" => '/register']);
			}
			
			// ECNET PART
			if($layout->modules()->isActivatedByName('ecnet')){
				$layout->setUser(new EcnetUser(0));
				$layout->user()->register($userId->id);
			}
			// ECNET PART END
			
			Database::commit();
			if($layout->lang() == "hu_HU" || $layout->lang() == "en_US")
				$lang = $layout->lang();
			else
				$lang = "hu_HU";
			Mail::send('mails.verification_'.$lang, ['name' => $request->input('name'), 'link' => url('/register/'.$string)], function ($m) use ($request, $layout) {
				$m->to($request->input('email'), $request->input('name'));
				$m->subject($layout->language('confirm_registration'));
			});
			Notifications::notifyAdminFromServer('accept_user_registration', $layout->language('new_user_registered'), $layout->language('new_user_registered_description'), 'admin/registration/show');
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_sending_registration_verification_email'),
											"url" => '/register']);
		}
    }
	
	public function verify($code){
		$layout = new LayoutData();
		$user = $layout->registrations()->getRegistrationByCode($code);
		if($user !== null){
			if($layout->registrations()->verify($code) === 0){
				return view('success.success', [
						"layout" => $layout,
						"message" => $layout->language('success_at_verifying_the_registration'),
						"url" => '/register'
					]);
			}else{
				return view('errors.error', [
						"layout" => $layout,
						"message" => $layout->language('error_at_verifying_the_registration'),
						"url" => '/register'
					]);
			}
		}else{
			return view('errors.error', [
					"layout" => $layout,
					"message" => $layout->language('error_at_verifying_the_registration'),
					"url" => '/register'
				]);
		}
	}
}
