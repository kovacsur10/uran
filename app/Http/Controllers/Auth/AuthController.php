<?php

namespace App\Http\Controllers\Auth;

use App\Classes\LayoutData;
use App\Classes\Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** Class name: AuthController
 *
 * This controller is for handling the authentications.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class AuthController extends Controller{
	
	/** Function name: showLoginForm
	 *
	 * This function shows the login page.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function showLoginForm(){
        return view('auth.login', ["layout" => new LayoutData()]);
    }
	
    /** Function name: login
     *
     * This function handles the login process.
     * 
     * @param Request $request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function login(Request $request){
		$this->validateLoginData($request);
		
		try{
			Auth::login($request->username, $request->password);
			return view('home', ["layout" => new LayoutData()]);
		}catch(\Exception $ex){
			$layout = new LayoutData();
			return view('errors.error', ["layout" => $layout,
					"message" => $layout->language('unsuccessful_login'),
					"url" => '/login']);
		}
	}
	
	/** Function name: logout
	 *
	 * This function handles the logout process.
	 *
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function logout(){
		Auth::logout();
		return view('auth.login', ["layout" => new LayoutData()]);
	}
	
	/** Function name: validateLoginData
	 *
	 * This function validates the login request data.
	 *
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function validateLoginData(Request $request){
        $this->validate($request, [
            'username' => 'required',
			'password' => 'required',
        ]);
    }
	
}
