<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Auth;
use App\Classes\LayoutData;
use App\Classes\Layout\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

/** Class name: PasswordController
 *
 * This controller is for the password resetting.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class PasswordController extends Controller{
	
// PUBLIC FUNCTIONS
	
	/** Function name: showResetForm
	 *
	 * This function shows the first password resetting page.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showResetForm(){
        return view('auth.password.reset', ["layout" => new LayoutData()]);
    }
	
    /** Function name: reset
     *
     * This function tries to start the resettinf of the password
     * for the user.
     * 
     * @param Request $request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function reset(Request $request){
		$this->validate($request, [
            'username' => 'required',
		]);
		$layout = new LayoutData();
		
		try{
			Auth::resetPassword($request->username);
		}catch(\Exception $ex){
			$layout = new LayoutData();
			return view('errors.error', ["layout" => $layout,
					"message" => $layout->language('error_at_reseting_password'),
					"url" => '/password/reset']);
		}
		return view('success.success', ["layout" => $layout,
										"message" => $layout->language('success_send_email_about_what_to_do'),
										"url" => '/']);
	}
	
	/** Function name: showPasswordForm
	 *
	 * This function validates the password resetting.
	 * If it's ok, it prompts for the new password.
	 *
	 * @param text $username
	 * @param text $code
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showPasswordForm($username, $code){
		try{
			$success = Auth::endPasswordReset($username, $code);
		}catch(\Exception $ex){
			$success = false;
		}
		if($success){
			return view('auth.password.email', ["layout" => new LayoutData(),
											    "username" => $username]);
		}else{
			$layout = new LayoutData();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_reseting_password'),
										 "url" => '/password/reset']);
		}
    }
	
    /** Function name: showPasswordForm
     *
     * This function sets the new password for the user.
     *
     * @param Request $request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function completeReset(Request $request){
		$layout = new LayoutData();
		//validation part
		$this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:8|max:64|confirmed||regex:/(^[A-Za-z0-9\-_\/]+$)/',
		]);
		
		try{
			Auth::updatePassword($request->input('username'), $request->input('password'));
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
					"message" => $layout->language('error_at_reseting_password'),
					"url" => '/password/reset']);
		}
		return view('success.success', ["layout" => $layout,
										"message" => $layout->language('success_at_reset_password'),
										"url" => '/']);
	}
}
