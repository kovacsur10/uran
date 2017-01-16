<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\LayoutData;
use App\Classes\Logger;
use App\Classes\Layout\EcnetData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class AdminController extends Controller{	
	
	public function showUsers($count = 50, $first = 0){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id));
		if(Session::has('ecnet_username_filter') && Session::has('ecnet_name_filter')){
			$layout->user()->filterUsers(Session::get('ecnet_username_filter'), Session::get('ecnet_name_filter'));
		}
		return view('ecnet.showusers', ["layout" => $layout,
										"usersToShow" => $count,
										"firstUser" => $first]);
	}
	
	public function showActiveUsers($type){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id));
		if($type == "name" || $type == "username" || $type == "both"){
			return view('ecnet.showactiveusers.'.$type, ["logged" => Session::has('user'),
												  "layout" => $layout]);
		}else{
			Logger::warning('Error at showActiveUsers, type mismatch (expected: name, username or both; given: '.print_r($type ,true).').', null, null, 'ecnet/users');
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_page_not_found'),
										 "url" => '/ecnet/users']);
		}
	}
	
	public function filterUsers(Request $request){
		if($request->input('username') == null){
			Session::put('ecnet_username_filter', '');
		}else{
			Session::put('ecnet_username_filter', $request->input('username'));
		}
		if($request->input('name') == null){
			Session::put('ecnet_name_filter', '');
		}else{
			Session::put('ecnet_name_filter', $request->input('name'));
		}
		return redirect('ecnet/users');
	}
	
	public function resetFilterUsers(){
		Session::forget('ecnet_username_filter');
		Session::forget('ecnet_name_filter');
		return redirect('ecnet/users');
	}
	
}
