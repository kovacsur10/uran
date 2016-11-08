<?php

namespace App\Classes;

use Carbon\Carbon;
use App\Classes\LayoutData;
use App\Classes\Logger;
use App\Persistence\P_User;

/** Class name: Auth
 *
 * This class handles the basic authentication
 * functionalities.
 *
 * Functionality:
 * 		- transaction handling
 * 
 * Functions that can throw exceptions:
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Auth{
	
// PUBLIC FUNCTIONS

	/** Function name: updateLoginDate
	 *
	 * This function updates the login date for the
	 * user, who logged in to the page.
	 * 
	 * @param text $username - the user's username
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function updateLoginDate($username){
		try{
			P_User::updateUserLoginTime($username, Carbon::now()->toDateTimeString());
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update 'users' was not successful! ".$ex->getMessage());
		}
	}
	
	 /** Function name: setUserLanguage
	 *
	 * This function sets the language of the page
	 * based on the user's saved data.
	 * 
	 * @param text $username - the user's username
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function setUserLanguage($username){
		try{
			$user = P_User::getUserByUsername($username);
		}catch(Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from 'users' was not successful! ".$ex->getMessage());
		}
		LayoutData::setLanguage($user->language);
	}
	
	/** Function name: getUser
	 *
	 * This function returns the requested user's data.
	 * 
	 * @param text $username - the user's username
	 * @return User data|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getUser($username){
		try{
			$user = P_User::getUserByUsername($username);
		}catch(Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from 'users' was not successful! ".$ex->getMessage());
		}
		return $user;
	}
	
	/** Function name: updatePassword
	 *
	 * This function changes the password of
	 * the requested user.
	 * 
	 * @param text $username - the user's username
	 * @param text $password - the user's password
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function updatePassword($username, $password){
		try{
			P_User::updateUserPassword($username, $password);
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'users' was not successful! ".$ex->getMessage());
		}
	}
	
// PRIVATE FUNCTIONS
	
}
