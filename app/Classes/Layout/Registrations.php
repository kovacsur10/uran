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
use App\Classes\Layout\EcnetData;
use App\Exceptions\UserNotFoundException;
use Mail;
use App\Classes\Data\Permission;

/** Class name: Registrations
 *
 * This class handles the user registrations
 * for the page.
 *
 * Functionality:
 * 		- registration for guests and collegists
 * 		- registartion accept/reject
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
	 * @return arrayOfUser
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function get(){
		try{
			$users = P_User::getRegistrationUsers();
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("User registration rejection failed!");
		}
	}
	
	/** Function name: acceptGuest
	 * 
	 * This function accepts a guest user registration.
	 * 
	 * @param int $userId
	 * @param text $country
	 * @param text $shire
	 * @param text $postalCode
	 * @param text $address
	 * @param text $city
	 * @param text $phone
	 * @param text $reason
	 * 
	 * @throws ValueMismatchException when the user identifier is null!
	 * @throws DatabaseException when the registration acception fails.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function acceptGuest($userId, $country, $shire, $postalCode, $address, $city, $phone, $reason){
		if($userId === null){
			throw new ValueMismatchException("The user identifier must not be null!");
		}
		$status = P_User::getStatusCodeByName('visitor');
		if($status === null){
			throw new DatabaseException("User status code error!");
		}
		try{
			P_User::promoteRegistrationUserToUser($userId, $country, $shire, $postalCode, $address, $city, $phone, $reason, null, null, null, null, null, null, null, null, null, $status->id());
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Guest registration acceptation failed!");
		}
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
	 * @param arrayOfInt $faculties
	 * @param arrayOfInt $workshops
	 * 
	 * @throws ValueMismatchException when the user identifier is null!
	 * @throws DatabaseException when the registration acception fails.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function acceptCollegist($userId, $country, $shire, $postalCode, $address, $city, $phone, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptunCode, $applicationYear, $faculties, $workshops){
		if($userId === null){
			throw new ValueMismatchException("The user identifier must not be null!");
		}
		try{
			P_User::promoteRegistrationUserToUser($userId, $country, $shire, $postalCode, $address, $city, $phone, 'Uran: Collegist registration', $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptunCode, $applicationYear, $faculties, $workshops, 0);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Collegist registration acceptation failed!");
		}
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
	 * @param arrayOfInt|null $faculties - in case of 'collegist' user
	 * @param arrayOfInt|null $workshops - in case of 'collegist' user
	 * 
	 * @throws ValueMismatchException when the provided data is not correct, it cannot be a 'guest' or 'collegist' user.
	 * @throws DatabaseException when the registration cannot be made because of a database error.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function register($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $reason, $phoneNumber, $defaultLanguage, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculties, $workshops){		
		//validate input data
		if($username === null || $password === null || $email === null || $name === null || $country === null || $shire === null || $postalCode === null ||$address === null || $city === null || $phoneNumber === null || $defaultLanguage === null){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). A parameter value is null! The following parameters are null: "
					.($username === null ? " username" : "" )
					.($password === null ? " password" : "" )
					.($email === null ? " email" : "" )
					.($name === null ? " name" : "" )
					.($shire === null ? " shite" : "" )
					.($postalCode === null ? " postalCode" : "" )
					.($address === null ? " address" : "" )
					.($city === null ? " city" : "" )
					.($phoneNumber === null ? " phoneNumber" : "" )
					.($defaultLanguage === null ? " defaultLanguage" : "" )
			);
			throw new ValueMismatchException("A mandatory parameter is null!");
		}
		$password = password_hash($password, PASSWORD_DEFAULT);
		if($reason === null){
			$dateOfBirth = $dateOfBirth === null ? null : substr(str_replace('.', '-', $dateOfBirth), 0, -1);
			if($cityOfBirth === null || $dateOfBirth === null || $nameOfMother === null || $yearOfLeavingExam === null || $highSchool === null || $neptun === null || $applicationYear === null ||$faculties === null || $workshops === null){
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
			$faculties = null;
			$workshops = null;
			$userType = 'guest';
		}
		$date = Carbon::now()->toDateTimeString();
		$registrationCode = sha1($username . $date . $email);
		
		//add registration to the database
		try{
			Database::transaction(function() use($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $reason, $phoneNumber, $defaultLanguage, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculties, $workshops, $date, $registrationCode, $userType){
				try{
					P_User::addRegistrationData($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $reason, $phoneNumber, $defaultLanguage, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculties, $workshops, $date);
					$userId = P_User::getRegistrationUserIdForUsername($username);
				}catch(\Exception $ex){
					Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
					throw $ex;
				}
				
				if($userId === null){
					Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). The registration user was not found!");
					throw new UserNotFoundException();
				}else{
					P_User::insertOrUpdateRadiusPassword($userId, $username, $password);
					P_User::addRegistrationCodeEntry($userId, $registrationCode);
					Registrations::addUserDefaultPermissionsGroups($userType, $userId);

					// ECNET PART
					$layout = new LayoutData();
					if($layout->modules()->isActivatedByName('ecnet')){
						$layout->setUser(new EcnetData(0));
						$layout->user()->register($userId);
					}
					// ECNET PART END
					//set up the language for the e-mail
					if($layout->lang() == "hu" || $layout->lang() == "en"){
						$lang = $layout->lang();
					}else{
						$lang = "hu";
					}
					//send e-mail
					Mail::send('mails.verification_'.$lang, ['name' => $name, 'link' => url('/register/'.$registrationCode)], function ($m) use ($email, $name, $layout) {
						$m->to($email, $name);
						$m->subject(__('auth.confirm_registration'));
					});
				}
			});
		}catch(\Exception $ex){
			throw new DatabaseException("Registration could not be done!");
		}
	}
	
// PRIVATE FUNCTIONS
	/** Function name: addUserDefaultPermissionsGroups
	 *
	 * This function accepts a collegist or a guest user registration.
	 *
	 * @param string $userType - user type ("collegist" or "guest")
	 * @param int $userId - user's identifier
	 *
	 * @throws ValueMismatchException
	 * @throws DatabaseException
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	
	private static function addUserDefaultPermissionsGroups($userType, $userId){
		if($userType === 'collegist'){
			Permissions::saveUserPermissionGroups($userId, [2]);
		}else if($userType === 'guest'){
			Permissions::saveUserPermissionGroups($userId, [1]);
		}
	}
}
