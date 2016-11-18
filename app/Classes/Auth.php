<?php

namespace App\Classes;

use Carbon\Carbon;
use App\Classes\LayoutData;
use App\Classes\Logger;
use App\Persistence\P_User;
use App\Classes\Layout\User;
use Illuminate\Support\Facades\Session;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;

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
	/** Function name: logout
	 *
	 * This function logs out the current user.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function logout(){
		if(Session::has('user')){
			Session::forget('user');
		}
	}
	
	/** Function name: login
	 *
	 * This function logs out the current user.
	 * 
	 * @param string $username - the login user's name
	 * @param string $password - the login user's password
	 *
	 * @throws DatabaseException It's thrown when the user login time could not be updated.
	 * @throws ValueMismatchException The given password did not match with the one in the database.
	 * @throws UserNotFoundException The given user could not be found.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function login($username, $password){
		$user = User::getUserDataByUsername(strtolower($username));
		if(password_verify($password, $user->password)){
			try{
				P_User::updateUserLoginTime($username, Carbon::now()->toDateTimeString());
			}catch(\Illuminate\Database\QueryException $e){
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
				throw new DatabaseException("User login time could not be updated!");
			}
			LayoutData::setLanguage($user->language);
			Session::put('user', $user);
		}else{ //password doesn't match
			throw new ValueMismatchException("Password mismatch!");
		}
	}
	
	/** Function name: updatePassword
	 *
	 * This function changes the password of
	 * the requested user.
	 * 
	 * @param text $username - the user's username
	 * @param text $password - the user's password
	 * 
	 * @throws UserNotFoundException when the username was not associated with a real user.
	 * @throws DatabaseException when the password update failed due to a persistence layer error.
	 * @throws ValueMismatchException when the password is null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function updatePassword($username, $password){
		if($password === null){
			throw new ValueMismatchException("Password cannot be null!");
		}
		$username = strtolower($username);
		User::getUserDataByUsername($username); //throws exception when user was not found
		try{
			P_User::updateUserPassword($username, password_hash($password, PASSWORD_DEFAULT));
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'users' was not successful! ".$ex->getMessage());
			throw new DatabaseException("Password update failed!");
		}
	}
	
// PRIVATE FUNCTIONS
	
}
