<?php

namespace App\Http\Controllers;

use App\Classes\LayoutData;
use App\Classes\Auth;
use App\Http\Controllers\Auth\AuthController;

/** Class name: HomeController
 *
 * This controller is for handling website home page related things.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class HomeController extends Controller
{
	/** Function name: __construct
	 *
	 * This function creates a new controller instance.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /** Function name: index
     *
     * This function shows the application dashboard.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
    public function index(){
    	if(Auth::isLoggedIn()){
    		LayoutData::saveSession();
    		return view('home', ["layout" => new LayoutData()]);
    	}else{
    		return AuthController::showLoginForm();
    	}
    }
}
