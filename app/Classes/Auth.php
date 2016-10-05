<?php

namespace App\Classes;

use DB;
use Carbon\Carbon;
use App\Classes\LayoutData;
use App\Classes\Logger;

/* Class name: Auth
 *
 * This class handles the basic authentication
 * functionalities.
 *
 * Functionality:
 * 		- transaction handling
 * 
 * Functions that can throw exceptions:
 */
class Auth{
	
// PUBLIC FUNCTIONS

	/* Function name: updateLoginDate
	 * Input: $username (text) - the user's username
	 * Output: -
	 *
	 * This function updates the login date for the
	 * user, who logged in to the page.
	 */
	public static function updateLoginDate($username){
		try{
			DB::table('users')
				->where('username', 'LIKE', $username)
				->update(['last_online' => Carbon::now()->toDateTimeString()]);
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update 'users' was not successful! ".$ex->getMessage());
		}
	}
	
	 /* Function name: setUserLanguage
	 * Input: $username (text) - the user's username
	 * Output: -
	 *
	 * This function sets the language of the page
	 * based on the user's saved data.
	 */
	public static function setUserLanguage($username){
		try{
			$user = DB::table('users')
				->where('username', 'LIKE', $username)
				->first();
		}catch(Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from 'users' was not successful! ".$ex->getMessage());
		}
		LayoutData::setLanguage($user->language);
	}
	
	/* Function name: getUser
	 * Input: $username (text) - the user's username
	 * Output: User data
	 *
	 * This function returns the requested user's data.
	 */
	public static function getUser($username){
		try{
			$user = DB::table('users')
				->where('username', 'LIKE', $username)
				->where('registered', '=', 1)
				->first();
		}catch(Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from 'users' was not successful! ".$ex->getMessage());
		}
		return $user;
	}
	
	/* Function name: updatePassword
	 * Input:	$username (text) - the user's username
	 * 			$password (text) - the user's password
	 * Output: -
	 *
	 * This function changes the password of
	 * the requested user.
	 */
	public static function updatePassword($username, $password){
		try{
			DB::table('users')
	            ->where('username', 'LIKE', $username)
	            ->update(array('password' => password_hash($password, PASSWORD_DEFAULT)));
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'users' was not successful! ".$ex->getMessage());
		}
	}
	
// PRIVATE FUNCTIONS
	
}
