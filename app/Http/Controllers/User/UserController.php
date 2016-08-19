<?php

namespace App\Http\Controllers\User;

use App\Classes\LayoutData;
use App\Classes\Layout\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class UserController extends Controller{	
    public function showData(){
		return view('user.showdata', ["layout" => new LayoutData()]);
	}
	
	public function showPublicData($username){
		$layout = new LayoutData();
		$targetId = $layout->user()->getUserDataByUsername($username);
		if($targetId == null){
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_finding_the_user'),
										 "url" => '/home']);
		}else{
			return view('user.showpublicdata', ["layout" => $layout,
												"target" => new User($targetId->id)]);
		}
	}
}
