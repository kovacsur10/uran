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
		$regTime = Carbon::now();
		$request->merge(array('username' => strtolower($request->input('username'))));
        $string = sha1($request->input('username') . $regTime->toDateTimeString() . $request->input('email'));
        $this->validate($request, [
            'username' => 'required|min:6|max:32|unique:users|regex:/(^[A-Za-z0-9_\-]+$)/',
            'email' => 'required|email|max:255|unique:users|unique:users',
            'password' => 'required|min:8|max:64|confirmed||regex:/(^[A-Za-z0-9\-_\/]+$)/',
            'name' => 'required',
			'country' => 'required',
			'shire' => 'required',
			'postalcode' => 'required',
			'address' => 'required',
			'city' => 'required',
			'reason' => 'required',
			'phone' => 'required',
		]);
		
		DB::beginTransaction(); //DATABASE TRANSACTION STARTS HERE
		DB::table('users')->insert([
			'username' => $request->input('username'),
            'password' => password_hash($request->input('password'), PASSWORD_DEFAULT),
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'registration_date' => $regTime->toDateTimeString(),
			'country' => $request->input('country'),
			'shire' => $request->input('shire'),
			'postalcode' => $request->input('postalcode'),
			'address' => $request->input('address'),
			'city' => $request->input('city'),
			'reason' => $request->input('reason'),
			'phone' => $request->input('phone'),
			'language' => $layout->lang(),
		]);
		$userId = DB::table('users')
			->select('id')
			->where('username', 'LIKE', $request->input('username'))
			->first();
		if($userId == null){
			DB::rollback();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_sending_registration_verification_email'),
										 "url" => '/register']);
		}else{
			DB::table('registrations')->insert([
				'user_id' => $userId->id,
				'code' => $string,
			]);
			
			// ECNET PART
			if($layout->modules()->isActivatedByName('ecnet')){
				DB::table('ecnet_user_data')->insert([
					'user_id' => $userId->id,
					'valid_time' => $regTime,
				]);
			}
			// ECNET PART END
			
			DB::commit();
			if($layout->lang() == "hu_HU" || $layout->lang() == "en_US")
				$lang = $layout->lang();
			else
				$lang = "hu_HU";
			Mail::send('mails.verification_'.$lang, ['name' => $request->input('name'), 'link' => 'http://host59.collegist.eotvos.elte.hu/register/'.$string], function ($m) use ($request, $layout) {
				$m->to($request->input('email'), $request->input('name'));
				$m->subject($layout->language('confirm_registration'));
			});
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_sending_registration_verification_email'),
											"url" => '/register']);
		}
    }
	
	public function registerCollegist(Request $request){
		$layout = new LayoutData();
		$regTime = Carbon::now();
		$request->merge(array('username' => strtolower($request->input('username'))));
        $string = sha1($request->input('username') . $regTime->toDateTimeString() . $request->input('email'));
        $this->validate($request, [
            'username' => 'required|min:6|max:32|unique:users|regex:/(^[A-Za-z0-9_\-]+$)/',
            'email' => 'required|email|max:255|unique:users|unique:users',
            'password' => 'required|min:8|max:64|confirmed||regex:/(^[A-Za-z0-9\-_\/]+$)/',
            'name' => 'required',
			'country' => 'required',
			'shire' => 'required',
			'postalcode' => 'required',
			'address' => 'required',
			'city' => 'required',
			'city_of_birth' => 'required',
			'name_of_mother' => 'required',
			'phone' => 'required',
			'year_of_leaving_exam' => 'required',
			'high_school' => 'required',
			'neptun' => 'required|min:6|max:6',
			'from_year' => 'required',
			'faculty' => 'required',
			'workshop' => 'required',
		]);
		$this->validate($request, array('date_of_birth' => array('required', 'regex:/(^(?:19[0-9]{2}|2[0-9]{3})\.(?:1[012]|0[1-9])\.(?:0[1-9]|[12][0-9]|3[01])\.$)/')));
		DB::beginTransaction(); //DATABASE TRANSACTION STARTS HERE
		DB::table('users')->insert([
			'username' => $request->input('username'),
            'password' => password_hash($request->input('password'), PASSWORD_DEFAULT),
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'registration_date' => $regTime->toDateTimeString(),
			'country' => $request->input('country'),
			'shire' => $request->input('shire'),
			'postalcode' => $request->input('postalcode'),
			'address' => $request->input('address'),
			'city' => $request->input('city'),
			'reason' => $request->input('reason'),
			'phone' => $request->input('phone'),
			'language' => $layout->lang(),
			'city_of_birth' => $request->input('city_of_birth'),
			'date_of_birth' => $request->input('date_of_birth'),
			'name_of_mother' => $request->input('name_of_mother'),
			'year_of_leaving_exam' => $request->input('year_of_leaving_exam'),
			'high_school' => $request->input('high_school'),
			'neptun' => $request->input('neptun'),
			'from_year' => $request->input('from_year'),
			'faculty' => $request->input('faculty'),
			'workshop' => $request->input('workshop'),
		]);
		$userId = DB::table('users')
			->select('id')
			->where('username', 'LIKE', $request->input('username'))
			->first();
		if($userId == null){
			DB::rollback();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_sending_registration_verification_email'),
										 "url" => '/register']);
		}else{
			DB::table('registrations')->insert([
				'user_id' => $userId->id,
				'code' => $string,
			]);
			
			// ECNET PART
			if($layout->modules()->isActivatedByName('ecnet')){
				DB::table('ecnet_user_data')->insert([
					'user_id' => $userId->id,
					'valid_time' => $regTime,
				]);
			}
			// ECNET PART END
			
			DB::commit();
			if($layout->lang() == "hu_HU" || $layout->lang() == "en_US")
				$lang = $layout->lang();
			else
				$lang = "hu_HU";
			Mail::send('mails.verification_'.$lang, ['name' => $request->input('name'), 'link' => 'http://host59.collegist.eotvos.elte.hu/register/'.$string], function ($m) use ($request, $layout) {
				$m->to($request->input('email'), $request->input('name'));
				$m->subject($layout->language('confirm_registration'));
			});
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_sending_registration_verification_email'),
											"url" => '/register']);
		}
    }
	
	public function vefify($code){
		$layout = new LayoutData();
		$user = DB::table('registrations')
			->where('code', 'LIKE', $code)
			->first();
		if($user != null){
			DB::table('registrations')
				->where('code', 'LIKE', $code)
				->update(['verified' => 1,
						  'verification_date' => Carbon::now()]);
				
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_verifying_the_registration'),
											"url" => '/register']);
		}else{
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_verifying_the_registration'),
										 "url" => '/register']);
		}
	}
}
