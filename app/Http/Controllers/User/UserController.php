<?php

namespace App\Http\Controllers\User;

use App\Classes\LayoutData;
use App\Classes\Layout\User;
use App\Http\Controllers\Controller;

/** Class name: UserController
 *
 * This controller is for handling the user data related things.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class UserController extends Controller{
	
	/** Function name: showData
	 *
	 * This function shows the user data page.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function showData(){
		return view('user.showdata', ["layout" => new LayoutData()]);
	}
	
	/** Function name: showPublicData
	 *
	 * This function shows the public user data page.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showPublicData($username){
		$layout = new LayoutData();
		try{
			$targetId = $layout->user()->getUserDataByUsername($username);
			return view('user.showpublicdata', ["layout" => $layout,
					"target" => new User($targetId->id)]);
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_finding_the_user'),
										 "url" => '/home']);
		}
	}
}
