<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use App\Classes\Notify;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DB;
use Mail;

class RegistrationController extends Controller{

    public function showList(){
		$layout = new LayoutData();
		if($layout->user()->permitted('accept_user_registration')){
			return view('admin.registration.list', ["layout" => $layout]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
	public function show($id){
		$layout = new LayoutData();
		$layout->registrations()->setRegistrationUserById($id);
		if($layout->user()->permitted('accept_user_registration')){
			return view('admin.registration.show', ["layout" => $layout]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
	public function reject($id){
		$layout = new LayoutData();
		if($layout->user()->permitted('accept_user_registration')){
			try{
				DB::table('users')
					->where('id', '=', $id)
					->where('registered', '=', 0)
					->where('id', '!=', 0)
					->delete();
			}catch(\Illuminate\Database\QueryException $e){
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('reject_user_registration_failure'),
											 "url" => '/admin/registration/show']);
			}	
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('reject_user_registration_success'),
											"url" => '/admin/registration/show']);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
	public function accept(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('accept_user_registration')){
			if($request->neptun == null || $request->neptun == ""){
				$this->validate($request, [
					'country' => 'required',
					'shire' => 'required',
					'postalcode' => 'required',
					'address' => 'required',
					'city' => 'required',
					'reason' => 'required',
					'phone' => 'required',
				]);
			}else{
				$this->validate($request, [
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
				$this->validate($request, array('date_of_birth' => array('required', 'regex:/^((?:(?:19[0-9]{2}|2[0-9]{3})\.(?:1[012]|0[1-9])\.(?:0[1-9]|[12][0-9]|3[01])\.)|(?:(?:19[0-9]{2}|2[0-9]{3})-(?:1[012]|0[1-9])-(?:0[1-9]|[12][0-9]|3[01])(?: 00:00:00)?))$/')));
			}
			try{
				if($request->neptun == null || $request->neptun == ""){
					DB::table('users')
						->where('id', '=', $request->id)
						->update([
							'registered' => 1,
							'country' => $request->input('country'),
							'shire' => $request->input('shire'),
							'postalcode' => $request->input('postalcode'),
							'address' => $request->input('address'),
							'city' => $request->input('city'),
							'phone' => $request->input('phone'),
							'reason' => $request->input('reason'),
						]);
				}else{
					DB::table('users')
						->where('id', '=', $request->id)
						->update([
							'registered' => 1,
							'country' => $request->input('country'),
							'shire' => $request->input('shire'),
							'postalcode' => $request->input('postalcode'),
							'address' => $request->input('address'),
							'city' => $request->input('city'),
							'phone' => $request->input('phone'),
							'city_of_birth' => $request->input('city_of_birth'),
							'name_of_mother' => $request->input('name_of_mother'),
							'date_of_birth' => $request->input('date_of_birth'),
							'year_of_leaving_exam' => $request->input('year_of_leaving_exam'),
							'high_school' => $request->input('high_school'),
							'neptun' => $request->input('neptun'),
							'from_year' => $request->input('from_year'),
							'faculty' => $request->input('faculty'),
							'workshop' => $request->input('workshop'),
						]);
				}
			}catch(\Illuminate\Database\QueryException $e){
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('accept_user_registration_failure'),
											 "url" => '/admin/registration/show']);
			}					
			// send e-mail notification to the user
			if($layout->lang() == "hu_HU" || $layout->lang() == "en_US")
				$lang = $layout->lang();
			else
				$lang = "hu_HU";
			Mail::send('mails.accept_'.$lang, ['name' => $request->input('name')], function ($m) use ($request, $layout) {
				$m->to($request->input('email'), $request->input('name'));
				$m->subject($layout->language('registration_accepted'));
			});
			
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('accept_user_registration_success'),
											"url" => '/admin/registration/show']);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
}
