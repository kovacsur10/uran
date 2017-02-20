<?php

namespace App\Http\Controllers\Auth;

use App\Classes\LayoutData;
use App\Classes\Notifications;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** Class name: RegisterController
 *
 * This controller is for the registering.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class RegisterController extends Controller{	
	
	/** Function name: showRegistrationChooserForm
	 *
	 * This function shows the registration choosing windows.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showRegistrationChooserForm(){
        return view('auth.chooseregister', ["layout" => new LayoutData()]);
    }
	
    /** Function name: showCollegistRegistrationForm
     *
     * This function shows the registration page for the collegists.
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function showCollegistRegistrationForm(){
        return view('auth.register.collegist', ["layout" => new LayoutData()]);
    }
	
    /** Function name: showGuestRegistrationForm
     *
     * This function shows the registration page for the guests.
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function showGuestRegistrationForm(){
        return view('auth.register.guest', ["layout" => new LayoutData()]);
    }
	
    /** Function name: registerGuest
     *
     * This function makes a guest registration.
     * 
     * @param Request $request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function registerGuest(Request $request){
		$layout = new LayoutData();
		//validation part
		$request->merge(array('username' => strtolower($request->input('username'))));
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
		
		//registration part
        try{
        	$layout->registrations()->register($request->input('username'), $request->input('password'), $request->input('email'), $request->input('name'), $request->input('country'), $request->input('shire'), $request->input('postalcode'), $request->input('address'), $request->input('city'), $request->input('reason'), $request->input('phone'), $layout->lang(), null, null, null, null, null, null, null, null, null);
        }catch(\Exception $ex){
        	return view('errors.error', ["layout" => $layout,
        			"message" => $layout->language('error_at_sending_registration_verification_email'),
        			"url" => '/register']);
        }
		Notifications::notifyAdminFromServer('accept_user_registration', $layout->language('new_user_registered'), $layout->language('new_user_registered_description'), 'admin/registration/show');
		return view('success.success', ["layout" => $layout,
			"message" => $layout->language('success_at_sending_registration_verification_email'),
			"url" => '/register']);
    }
	
    /** Function name: registerCollegist
     *
     * This function makes a collegist registration.
     *
     * @param Request $request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function registerCollegist(Request $request){
		$layout = new LayoutData();
		//validation part
		$request->merge(array('username' => strtolower($request->input('username'))));
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
			'faculties' => 'required',
			'workshops' => 'required',
			'accept' => 'required',
		]);
		$this->validate($request, array('year_of_leaving_exam' => array('required', 'regex:/(^(?:19[6-9][0-9])|(?:200[0-9])|(?:201[0-6])$)/')));
		$this->validate($request, array('date_of_birth' => array('required', 'regex:/(^(?:19[0-9]{2}|2[0-9]{3})\.(?:1[012]|0[1-9])\.(?:0[1-9]|[12][0-9]|3[01])\.?$)/')));
		
		//registration part
		try{
			$layout->registrations()->register($request->input('username'), $request->input('password'), $request->input('email'), $request->input('name'), $request->input('country'), $request->input('shire'), $request->input('postalcode'), $request->input('address'), $request->input('city'), null, $request->input('phone'), $layout->lang(), $request->input('city_of_birth'), $request->input('date_of_birth'), $request->input('name_of_mother'), $request->input('year_of_leaving_exam'), $request->input('high_school'), $request->input('neptun'), $request->input('from_year'), $request->input('faculties'), $request->input('workshops'));
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
					"message" => $layout->language('error_at_sending_registration_verification_email'),
					"url" => '/register']);
		}
		Notifications::notifyAdminFromServer('accept_user_registration', $layout->language('new_user_registered'), $layout->language('new_user_registered_description'), 'admin/registration/show');
		return view('success.success', ["layout" => $layout,
			"message" => $layout->language('success_at_sending_registration_verification_email'),
			"url" => '/register']);
    }
	
    /** Function name: verify
     *
     * This function verifies a registration.
     *
     * @param string $code
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function verify($code){
		$layout = new LayoutData();
		try{
			$layout->registrations()->verify($code);
		}catch(\Exception $ex){
			return view('errors.error', [
					"layout" => $layout,
					"message" => $layout->language('error_at_verifying_the_registration'),
					"url" => '/register'
				]);
		}
		return view('success.success', [
				"layout" => $layout,
				"message" => $layout->language('success_at_verifying_the_registration'),
				"url" => '/register'
		]);
	}
}
