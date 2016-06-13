<?php

namespace App\Http\Controllers\User;

use App\Classes\LayoutData;
use App\Classes\User;
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
		$targetId = DB::table('users')
						->where('username', '=', $username)
						->select('id')
						->first();
		if($targetId == null)
			return view('errors.error', ["layout" => new LayoutData(),
										 "message" => 'A felhasználó nem található, vagy nem publikus az oldala!',
										 "url" => '/home']);
		else
			return view('user.showpublicdata', ["layout" => new LayoutData(),
												"target" => new User($targetId->id)]);
	}
}
