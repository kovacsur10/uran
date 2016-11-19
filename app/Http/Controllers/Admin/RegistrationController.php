<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
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
				$layout->registrations()->reject($id);
			}catch(\Expcetion $ex){
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
			if($request->neptun == null || $request->neptun == ""){
				$returnCode = $layout->registrations()->acceptGuest($request->id, $request->input('country'), $request->input('shire'), $request->input('postalcode'), $request->input('address'), $request->input('city'), $request->input('phone'), $request->input('reason'));
			}else{
				$returnCode = $layout->registrations()->acceptCollegist($request->id, $request->input('country'), $request->input('shire'), $request->input('postalcode'), $request->input('address'), $request->input('city'), $request->input('phone'), $request->input('city_of_birth'), $request->input('date_of_birth'), $request->input('name_of_mother'), $request->input('year_of_leaving_exam'), $request->input('high_school'), $request->input('neptun'), $request->input('from_year'), $request->input('faculty'), $request->input('workshop'));
			}
			if($returnCode !== 0){
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
