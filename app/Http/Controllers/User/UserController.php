<?php

namespace App\Http\Controllers\User;

use App\Classes\LayoutData;
use App\Classes\Layout\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class UserController extends Controller{	
    public function showData(){
		$country = DB::table('country')->where('id', 'LIKE', Session::get('country'))
									   ->first();;
		return view('user.showdata', ["layout" => new LayoutData(),
									  "country" => $country->name]);
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
