<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;

/** Class name: RegistrationController
 *
 * This controller is for handling the registrations.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class RegistrationController extends Controller{

	/** Function name: showList
	 *
	 * This function shows the registrations.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function showList(){
		$layout = new LayoutData();
		if($layout->user()->permitted('accept_user_registration')){
			return view('admin.registration.list', ["layout" => $layout]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
    /** Function name: show
     *
     * This function shows the requested user registration request.
     * 
     * @param int id - registration user identifier
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function show($id){
		$layout = new LayoutData();
		$layout->registrations()->setRegistrationUser($id);
		if($layout->user()->permitted('accept_user_registration')){
			if($layout->registrations()->getRegistrationUser() === null){
				return view('errors.error', ["layout" => $layout,
						"message" => $layout->language('registration_user_not_found'),
						"url" => '/admin/registration/show']);
			}else{
				return view('admin.registration.show', ["layout" => $layout]);
			}
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
    /** Function name: reject
     *
     * This function rejects a user registration.
     * 
     * @param int id - registration user identifier
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
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
	
    /** Function name: accept
     *
     * This function accepts a user registration.
     *
     * @param Request request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
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
					'faculties' => 'required',
					'workshops' => 'required',
				]);
				$this->validate($request, array('date_of_birth' => array('required', 'regex:/^((?:(?:19[0-9]{2}|2[0-9]{3})\.(?:1[012]|0[1-9])\.(?:0[1-9]|[12][0-9]|3[01])\.)|(?:(?:19[0-9]{2}|2[0-9]{3})-(?:1[012]|0[1-9])-(?:0[1-9]|[12][0-9]|3[01])(?: 00:00:00)?))$/')));
			}
			try{
				if($request->neptun == null || $request->neptun == ""){
					$layout->registrations()->acceptGuest($request->id, $request->input('country'), $request->input('shire'), $request->input('postalcode'), $request->input('address'), $request->input('city'), $request->input('phone'), $request->input('reason'));
				}else{
					$layout->registrations()->acceptCollegist($request->id, $request->input('country'), $request->input('shire'), $request->input('postalcode'), $request->input('address'), $request->input('city'), $request->input('phone'), $request->input('city_of_birth'), $request->input('date_of_birth'), $request->input('name_of_mother'), $request->input('year_of_leaving_exam'), $request->input('high_school'), $request->input('neptun'), $request->input('from_year'), $request->input('faculties'), $request->input('workshops'));
				}
			}catch(\Exception $ex){
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
