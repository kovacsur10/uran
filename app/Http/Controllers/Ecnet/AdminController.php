<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\Auth;
use App\Classes\Logger;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Classes\Layout\EcnetData;

/** Class name: AccessController
 *
 * This controller is for handling the internet access.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class AdminController extends Controller{	
	/** Function name: showUsers
	 *
	 * This function shows the ECnet user administrator page.
	 * 
	 * @param int $count - count of users to show
	 * @param int $first - first user's identifier to show
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showUsers($count = 50, $first = 0){
		$layout = SharedController::getEcnetLayout();
		$layout->user()->filterUsers();
		return view('ecnet.showusers', ["layout" => $layout,
										"usersToShow" => $count,
										"firstUser" => $first]);
	}
	
	/** Function name: showActiveUsers
	 *
	 * This function shows the ECnet users list
	 * based on a type filter.
	 *
	 * @param string $type - flag
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showActiveUsers($type){
		$layout = SharedController::getEcnetLayout();
		if($type === "name" || $type === "username" || $type === "both"){
			return view('ecnet.showactiveusers.'.$type, ["logged" => Auth::isLoggedIn(),
												  "layout" => $layout]);
		}else{
			Logger::warning('Error at showActiveUsers, type mismatch (expected: name, username or both; given: '.print_r($type ,true).').', null, null, 'ecnet/users');
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_page_not_found'),
										 "url" => '/ecnet/users']);
		}
	}
	
	/** Function name: filterUsers
	 *
	 * This function filters the list of ECnet users.
	 *
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function filterUsers(Request $request){
		EcnetData::setFilterUsers($request->input('username'), $request->input('name'));
		return redirect('ecnet/users');
	}
	
	/** Function name: resetFilterUsers
	 *
	 * This function resets the filters.
	 *
	 * @param string $type - flag
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function resetFilterUsers(){
		EcnetData::resetFilterUsers();
		return redirect('ecnet/users');
	}
	
}
