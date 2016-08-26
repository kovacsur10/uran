<?php

namespace App\Http\Controllers\ECAdmin;

use App\Classes\LayoutData;
use App\Classes\Layout\User;
use App\Classes\Notify;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller{

	public function show(){
		$layout = new LayoutData();
        return view('ecadmin.users.list', ["layout" => $layout]);
    }
	
	public function showUser($userId){
		$layout = new LayoutData();
        return view('ecadmin.users.show', ["layout" => $layout,
										   "target" => new User($userId)]);
    }
	
}
