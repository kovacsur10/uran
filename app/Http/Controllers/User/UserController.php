<?php

namespace App\Http\Controllers\User;

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
		return view('user.showdata', ["logged" => Session::has('user'),
									  "user" => new User(Session::get('user')->id),
									  "country" => $country->name]);
	}
}
