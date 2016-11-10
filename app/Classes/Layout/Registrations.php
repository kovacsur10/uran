<?php

namespace App\Classes\Layout;

use Carbon\Carbon;
use App\Persistence\P_User;
use App\Persistence\P_General;

/** Class name: Registrations
 *
 * This class handles the user registrations
 * for the page.
 *
 * Functionality:
 * 		- registration for guests and collegists
 * 		- registartion accept/reject
 *
 * Functions that can throw exceptions:
 * 		addCode
 * 		addUserDefaultPermissions
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Registrations{
	
// PRIVATE VARIABLES	
	
	private $registrationUser = null;	
	
// PUBLIC FUNCTIONS	
	
	/** Function name: __construct
	 *
	 * The constructor of the class Registrations.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(){
	}
	
	/** Function name: getRegistrationUser
	 *
	 * The getter function of the registration user.
	 * 
	 * @return User
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getRegistrationUser(){
		return $this->registrationUser;
	}
	
	/** Function name: get
	 *
	 * This function returns the
	 * registered, but not accepted
	 * or rejected users.
	 * 
	 * @return array of Users
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function get(){
		try{
			$users = P_User::getRegistrationUsers();
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
		}
		return $users;
	}
	
	/** Function name: setRegistrationUser
	 *
	 * This function sets the private
	 * variable $registrationUser to the
	 * requested registration user.
	 * 
	 * @param int $userId - the user's identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function setRegistrationUser($userId){
		try{
			$user = P_User::getRegistrationUserById($userId);
		}catch(\Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users', joined to 'registrations' was not successful! ".$ex->getMessage());
		}
		$this->registrationUser = $user;
	}
		
	/** Function name: verify
	 *
	 * This function tries to verify
	 * the registration user's e-mail
	 * address based on the verification code.
	 * 
	 * @param text $code - registration code
	 * @return int - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function verify($code){
		$errorCode = 0;
		try{
			P_User::verifyRegistrationUser($code, Carbon::now()->toDateTimeString());
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'registrations' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
	/** Function name: getNotVerifiedUserData
	 *
	 * This function returns the requested
	 * registered, but not accepted
	 * or rejected user.
	 * 
	 * @param text $username - user's text identifier
	 * @return User|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getNotVerifiedUserData($username){
		try{
			$user = P_User::getRegistrationUserByUsername($username);
		}catch(\Illuminate\Database\QueryException $e){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
		}
		return $user;
	}
	
	/** Function name: addCode
	 *
	 * This function returns the requested
	 * registered, but not accepted
	 * or rejected user.
	 * 
	 * THROWS EXCEPTIONS!
	 * 
	 * @param int $userId - user's identifier
	 * @param text $code - registration code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addCode($userId, $code){
		addRegistrationCodeEntry($userId, $code);
	}
	
	/** Function name: insertGuestData
	 * 
	 * This function registers a guest user.
	 * 
	 * @param text $username - user's username
	 * @param text $password - user's password
	 * @param text $email - user's e-mail address
	 * @param text $name - user's name
	 * @param text $country - user's country (address)
	 * @param text $shire - user's shire (address)
	 * @param text $postalCode - user's postal code (address)
	 * @param text $address - user's address (address)
	 * @param text $city - user's city (address)
	 * @param text $reasonOfRegistration - user's reason of registration
	 * @param text $phoneNumber - user's phone number
	 * @param text $defaultLanguage - user's default language
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function insertGuestData($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $reasonOfRegistration, $phoneNumber, $defaultLanguage){
		try{
			$date = Carbon::now()->toDateTimeString();
			P_User::addRegistrationData($username, password_hash($password, PASSWORD_DEFAULT), $email, $name, $country, $shire, $postalCode, $address, $city, $reasonOfRegistration, $phoneNumber, $defaultLanguage, null, null, null, null, null, null, null, null, null, $date);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'users' was not successful! ".$ex->getMessage());
		}
	}

	/** Function name: insertCollegistData
	 * 
	 * This function registers a guest user.
	 * 
	 * @param text $username
	 * @param text $password
	 * @param text $email
	 * @param text $name
	 * @param text $country
	 * @param text $shire
	 * @param text $postalCode
	 * @param text $address
	 * @param text $city
	 * @param text $phoneNumber
	 * @param text $defaultLanguage
	 * @param text $cityOfBirth
	 * @param datetime $dateOfBirth
	 * @param text $nameOfMother
	 * @param int $yearOfLeavingExam
	 * @param text $highSchool
	 * @param text $neptun
	 * @param int $applicationYear
	 * @param int $faculty
	 * @param int $workshop
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function insertCollegistData($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $phoneNumber, $defaultLanguage, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculty, $workshop){
		try{
			$date = Carbon::now()->toDateTimeString();
			P_User::addRegistrationData($username, password_hash($password, PASSWORD_DEFAULT), $email, $name, $country, $shire, $postalCode, $address, $city, null, $phoneNumber, $defaultLanguage, $cityOfBirth, substr(str_replace('.', '-', $dateOfBirth), 0, -1), $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculty, $workshop, $date);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'users' was not successful! ".$ex->getMessage());
		}
	}
	
	/** Function name: addUserDefaultPermissions
	 *
	 * This function gives the registered user
	 * the default permissions based on the user
	 * type.
	 * 
	 * THROWS EXCEPTIONS!
	 * 
	 * @param text $userType - user's type
	 * @param int $userId - user's identifier
	 * @return int - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addUserDefaultPermissions($userType, $userId){
		//get the default permissions
		$permissions = P_General::getDefaultPermissions();
		//set the user permissions
		foreach($permissions as $permission){
			P_User::addPermissionForUser($userId, $permission->permission);
		}
	}
	
	/** Function name: reject
	 *
	 * This function rejects a user
	 * registration.
	 * 
	 * @param int $userId - user's identifier
	 * @return int - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function reject($userId){
		$errorCode = 0;
		try{
			P_User::removeRegistrationUser($userId);
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'users' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
	/** Function name: acceptGuest
	 * 
	 * This function accepts a guest user registration.
	 * 
	 * Error codes:
	 * 		0 - success
	 * 		1 - user status code exception
	 * 		2 - user registration update exception
	 * 
	 * @param int $userId
	 * @param text $country
	 * @param text $shire
	 * @param text $postalCode
	 * @param text $address
	 * @param text $city
	 * @param text $phone
	 * @param text $reason
	 * @return int - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function acceptGuest($userId, $country, $shire, $postalCode, $address, $city, $phone, $reason){
		$errorCode = 0;
		try{
			$status = P_User::getStatusCodeByName('visitor');
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'user_status_codes' was not successful! ".$ex->getMessage());
		}
		if($errorCode === 0){
			try{
				P_User::promoteRegistrationUserToUser($userId, $country, $shire, $postalCode, $address, $city, $phone, $reason, null, null, null, null, null, null, null, null, null, $status->id);
			}catch(\Exception $ex){
				$errorCode = 2;
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'users' was not successful! ".$ex->getMessage());
			}
		}
		return $errorCode;
	}
	
	/** Function name: acceptCollegist
	 * 
	 * This function accepts a collegist user registration.
	 * 
	 * @param int $userId
	 * @param text $country
	 * @param text $shire
	 * @param text $postalCode
	 * @param text $address
	 * @param text $city
	 * @param text $phone
	 * @param text $cityOfBirth
	 * @param datetime $dateOfBirth
	 * @param text $nameOfMother
	 * @param int $yearOfLeavingExam
	 * @param text $highSchool
	 * @param text $neptunCode
	 * @param int $applicationYear
	 * @param int $faculty
	 * @param int $workshop
	 * @return int - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function acceptCollegist($userId, $country, $shire, $postalCode, $address, $city, $phone, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptunCode, $applicationYear, $faculty, $workshop){
		$errorCode = 0;
		try{
			P_User::promoteRegistrationUserToUser($userId, $country, $shire, $postalCode, $address, $city, $phone, 'Uran: Collegist registration', $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptunCode, $applicationYear, $faculty, $workshop, 0);
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'users' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
// PRIVATE FUNCTIONS

}
