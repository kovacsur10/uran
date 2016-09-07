<?php

namespace App\Classes;

use DB;
use Carbon\Carbon;
use App\Classes\LayoutData;

class Auth{
	
// PUBLIC FUNCTIONS

	public static function updateLoginDate($username){
		DB::table('users')
			->where('username', 'LIKE', $username)
			->update(['last_online' => Carbon::now()->toDateTimeString()]);
	}
	
	public static function setUserLanguage($username){
		$user = DB::table('users')
			->where('username', 'LIKE', $username)
			->first();
		LayoutData::setLanguage($user->language);
	}
	
	public static function getUser($username){
		return DB::table('users')
			->where('username', 'LIKE', $username)
			->where('registered', '=', 1)
			->first();
	}
	
	public static function updatePassword($username, $password){
		DB::table('users')
            ->where('username', 'LIKE', $username)
            ->update(array('password' => password_hash($password, PASSWORD_DEFAULT)));
	}
	
}
