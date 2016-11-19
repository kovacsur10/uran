<?php

namespace App\Classes\Layout;

use Carbon\Carbon;
use App\Persistence\P_User;
use App\Persistence\P_General;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;
use App\Classes\Database;
use App\Classes\LayoutData;
use App\Classes\Logger;

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
	
	private $registrationUser;	
	
// PUBLIC FUNCTIONS	
	
	/** Function name: __construct
	 *
	 * The constructor of the class Registrations.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(){
		$this->registrationUser = null;
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
	 * @return array of User
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function get(){
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
	 * 
	 * @throws DatabaseException when the code verification fails.
	 * @throws ValueMismatchException when the code is null.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function verify($code){
		if($code === null){
			throw new ValueMismatchException("The code cannot be null!");
		}
		try{
			P_User::verifyRegistrationUser($code, Carbon::now()->toDateTimeString());
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'registrations' was not successful! ".$ex->getMessage());
			throw new DatabaseException("Verifying the registration code was unsuccessful!");
		}
	}
	
	/** Function name: reject
	 *
	 * This function rejects a user
	 * registration.
	 * 
	 * @param int $userId - user's identifier
	 * 
	 * @throws DatabaseException when the rejection process fails.
	 * @throws ValueMismatchException when the user identifier is null.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function reject($userId){
		if($userId === null){
			throw new ValueMismatchException("The user identifier cannot be null!");
		}
		try{
			P_User::removeRegistrationUser($userId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'users' was not successful! ".$ex->getMessage());
			throw new DatabaseException("User registration rejection failed!");
		}
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
	public static function acceptGuest($userId, $country, $shire, $postalCode, $address, $city, $phone, $reason){
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
	public static function acceptCollegist($userId, $country, $shire, $postalCode, $address, $city, $phone, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptunCode, $applicationYear, $faculty, $workshop){
		$errorCode = 0;
		try{
			P_User::promoteRegistrationUserToUser($userId, $country, $shire, $postalCode, $address, $city, $phone, 'Uran: Collegist registration', $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptunCode, $applicationYear, $faculty, $workshop, 0);
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'users' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
	/** Function name: register
	 *
	 * This function register a user.
	 *
	 * @param text $username - mandatory
	 * @param text $password - mandatory
	 * @param text $email - mandatory
	 * @param text $name - mandatory
	 * @param text $country - mandatory
	 * @param text $shire - mandatory
	 * @param text $postalCode - mandatory
	 * @param text $address - mandatory
	 * @param text $city - mandatory
	 * @param text|null $reason - in case of 'guest' user
	 * @param text $phoneNumber - mandatory
	 * @param text $defaultLanguage - mandatory
	 * @param text|null $cityOfBirth - in case of 'collegist' user
	 * @param datetime|null $dateOfBirth - in case of 'collegist' user
	 * @param text|null $nameOfMother - in case of 'collegist' user
	 * @param int|null $yearOfLeavingExam - in case of 'collegist' user
	 * @param text|null $highSchool - in case of 'collegist' user
	 * @param text|null $neptun - in case of 'collegist' user
	 * @param int|null $applicationYear - in case of 'collegist' user
	 * @param int|null $faculty - in case of 'collegist' user
	 * @param int|null $workshop - in case of 'collegist' user
	 * 
	 * @throws ValueMismatchException when the provided data is not correct, it cannot be a 'guest' or 'collegist' user.
	 * @throws DatabaseException when the registration cannot be made because of a database error.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function register($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $reason, $phoneNumber, $defaultLanguage, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculty, $workshop){		
		//validate input data
		if($username === null || $password === null || $email === null || $name === null || $country === null || $shire === null || $postalCode === null ||$address === null || $city === null || $phoneNumber === null || $defaultLanguage === null){
			throw new ValueMismatchException("A mandatory parameter is null!");
		}
		$password = password_hash($password, PASSWORD_DEFAULT);
		if($reason === null){
			$dateOfBirth = substr(str_replace('.', '-', $dateOfBirth), 0, -1);
			if($cityOfBirth === null || $dateOfBirth === null || $nameOfMother === null || $yearOfLeavingExam === null || $highSchool === null || $neptun === null || $applicationYear === null ||$faculty === null || $workshop === null){
				throw new ValueMismatchException("A mandatory parameter is null!");
			}
			$userType = 'collegist';
		}else{
			$cityOfBirth = null;
			$dateOfBirth = null;
			$nameOfMother = null;
			$yearOfLeavingExam = null;
			$highSchool = null;
			$neptun = null;
			$applicationYear = null;
			$faculty = null;
			$workshop = null;
			$userType = 'guest';
		}
		$date = Carbon::now()->toDateTimeString();
		$registrationCode = sha1($username . $date . $email);
		
		//add registration to the database
		Database::beginTransaction(); //DATABASE TRANSACTION STARTS HERE
		try{
			P_User::addRegistrationData($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $reason, $phoneNumber, $defaultLanguage, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculty, $workshop, $date);
			$user = $this->getNotVerifiedUserData($username);
		}catch(\Illuminate\Database\QueryException $e){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			$this->registrationCleanUp();
		}
		if($user === null){
			$this->registrationCleanUp();
		}else{
			try{
				P_User::addRegistrationCodeEntry($user->id, $registrationCode);
				$this->addUserDefaultPermissions($userType, $user->id);
					
				// ECNET PART
				$layout = new LayoutData();
				if($layout->modules()->isActivatedByName('ecnet')){
					$layout->setUser(new EcnetUser(0));
					$layout->user()->register($user->id);
				}
				// ECNET PART END
				Database::commit();
				//set up the language for the e-mail
				if($layout->lang() == "hu_HU" || $layout->lang() == "en_US"){
					$lang = $layout->lang();
				}else{
					$lang = "hu_HU";
				}
				//send e-mail
				Mail::send('mails.verification_'.$lang, ['name' => $name, 'link' => url('/register/'.$registrationCode)], function ($m) use ($email, $name, $layout) {
					$m->to($email, $name);
					$m->subject($layout->language('confirm_registration'));
				});
			}catch(\Exception $ex){
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
				$this->registrationCleanUp();
			}
		}
	}
	
// PRIVATE FUNCTIONS
	/** Function name: getNotVerifiedUserData
	 *
	 * This function returns the requested
	 * registered, but not accepted
	 * or rejected user.
	 *
	 * @param text $username - user's text identifier
	 * @return User|null
	 *
	 * @throws DatabaseException is when the persistence layer returns an exception.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private static function getNotVerifiedUserData($username){
		try{
			$user = P_User::getRegistrationUserByUsername($username);
		}catch(\Illuminate\Database\QueryException $e){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
			throw new DatabaseException();
		}
		return $user;
	}
	
	/** Function name: addUserDefaultPermissions
	 *
	 * This function gives the registered user
	 * the default permissions based on the user
	 * type.
	 *
	 * @param text $userType - user's type
	 * @param int $userId - user's identifier
	 * @return int - error code
	 *
	 * @throws QueryException when the persistence layer fails.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private static function addUserDefaultPermissions($userType, $userId){
		//get the default permissions
		$permissions = P_General::getDefaultPermissions();
		//set the user permissions
		foreach($permissions as $permission){
			P_User::addPermissionForUser($userId, $permission->permission);
		}
	}
	
	/** Function name: registrationCleanUp
	 *
	 * This function cleans up when an error
	 * occures during the registration.
	 *
	 * @throws DatabaseException always.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private static function registrationCleanUp(){
		try{
			Database::rollback();
		}catch(\Exception $ex){
		}
		throw new DatabaseException("Registration could not be done!");
	}
}
