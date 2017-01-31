<?php

namespace App\Http\Controllers\ECAdmin;

use App\Classes\LayoutData;
use App\Classes\Layout\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** Class name: UserController
 *
 * This controller is for handling the EC collegists related things.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class UserController extends Controller{

	/** Function name: show
	 *
	 * This function shows the collegist users.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function show(){
		$layout = new LayoutData();
        return view('ecadmin.users.list', ["layout" => $layout]);
    }
	
    /** Function name: showUser
     *
     * This function shows the requested user.
     * 
     * @param int $userId - user's identifier
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function showUser($userId){
		$layout = new LayoutData();
        return view('ecadmin.users.show', ["layout" => $layout,
										   "target" => new User($userId)]);
    }
	
    /** Function name: updateUser
     *
     * This function updates a user's data.
     * 
     * @param Request $request
     * @param int $userId - user's identifier
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function updateUser(Request $request, $userId){
		$layout = new LayoutData();
		if($layout->user()->permitted('user_handling')){
			//TODO
			return view('ecadmin.users.show', ["layout" => $layout,
											   "target" => new User($userId)]);
		}else{
			//TODO
		}
    }
	
}
