<?php

namespace App\Classes;

use Carbon\Carbon;
use App\Classes\LayoutData;
use App\Classes\Logger;
use App\Classes\Database;
use App\Persistence\P_User;
use App\Classes\Layout\User as MU;
use Illuminate\Contracts\Session\Session;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;
use App\Classes\Data\User;
use Mail;

/** Class name: Auth
 *
 * This class handles the basic authentication
 * functionalities.
 *
 * Functionality:
 * 		- transaction handling
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Auth{
	
// PUBLIC FUNCTIONS
	/** Function name: isLoggedIn
	 *
	 * This function returns if a user is logged in or not.
	 *
	 * @return bool - logged in or not
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function isLoggedIn(){
		return session()->has('user');
	}
	
	/** Function name: user
	 *
	 * This function returns the logged user.
	 *
	 * @return User|null - the logged user
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function user(){
		if(Auth::isLoggedIn()){
			return session()->get('user');
		}else{
			return null;
		}
	}
	
	/** Function name: logout
	 *
	 * This function logs out the current user.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function logout(){
		LayoutData::saveSession();
		$lang = LayoutData::lang();
		session()->flush();
		session()->put('lang', $lang);
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
		$user = MU::getUserDataByUsername(strtolower($username));
		if(password_verify($password, $user->password())){
			try{
				P_User::updateUserLoginTime($username, Carbon::now()->toDateTimeString());
			}catch(\Illuminate\Database\QueryException $e){
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
				throw new DatabaseException("User login time could not be updated!");
			}
			session()->put('user', $user);
			LayoutData::setLanguage($user->language());
			LayoutData::loadSession();
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
		$user = MU::getUserDataByUsername($username); //throws exception when user was not found
		try{
			Database::transaction(function() use($user, $username, $password){
				P_User::updateUserPassword($username, password_hash($password, PASSWORD_DEFAULT));
				P_User::insertOrUpdateRadiusPassword($user->id(), $username, $password);
			});
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Password update failed!");
		}
	}
	
	/** Function name: resetPassword
	 *
	 * This function start a password reset for the user.
	 *
	 * @param text $username - the user's username
	 *
	 * @throws UserNotFoundException when the username was not associated with a real user.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function resetPassword($username){
		$layout = new LayoutData();
		try{
			$user = MU::getUserDataByUsername($username);
		}catch(\Exception $ex){
			throw new UserNotFoundException();
		}

		$day = Carbon::now()->dayOfYear;
		$string = sha1($username.$user->registrationDate().$user->name().$day);
		$lang = \App::getLocale();
		Mail::send('mails.resetpwd_'.$lang, ['name' => $user->name(), 'link' => url('/password/reset/'.$user->username().'/'.$string)], function ($m) use ($user, $layout) {
			$m->to($user->email(), $user->name());
			$m->subject(__('auth.forgotten_password'));
		});
	}
	
	/** Function name: endPasswordReset
	 *
	 * This function validates a password reset for the user.
	 *
	 * @param text $username - the user's username
	 * @param text $code - the reset code
	 * @return boolean - successfully resetted or not
	 *
	 * @throws UserNotFoundException when the username was not associated with a real user.
	 * @throws ValueMismatchException if the provided code is null.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function endPasswordReset($username, $code){
		if($code === null){
			throw new ValueMismatchException("The code cannot be null!");
		}
		try{
			$user = MU::getUserDataByUsername($username);
		}catch(\Exception $ex){
			throw new UserNotFoundException();
		}
		$day = Carbon::now()->dayOfYear;
		$i = 0;
		while($i < 5 && sha1($username.$user->registrationDate().$user->name().$day) !== $code){
			$day--;
			if($day < 0)
				$day = 365;
				$i++;
		}
		return $i < 5;
	}
	
// PRIVATE FUNCTIONS
	
}
