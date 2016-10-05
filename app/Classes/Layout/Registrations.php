<?php

namespace App\Classes\Layout;

use DB;
use Carbon\Carbon;

/* Class name: Registrations
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
 */
class Registrations{
	
// PRIVATE VARIABLES	
	
	private $registrationUser = null;	
	
// PUBLIC FUNCTIONS	
	
	/* Function name: __construct
	 * Input: -
	 * Output: -
	 *
	 * The constructor of the class Registrations.
	 */
	public function __construct(){
	}
	
	/* Function name: getRegistrationUser
	 * Input: -
	 * Output: User
	 *
	 * The getter function of the registration user.
	 */
	public function getRegistrationUser(){
		return $this->registrationUser;
	}
	
	/* Function name: get
	 * Input: -
	 * Output: array of users
	 *
	 * This function returns the
	 * registered, but not accepted
	 * or rejected users.
	 */
	public function get(){
		try{
			$users = DB::table('users')
				->where('registered', '=', 0)
				->where('id', '!=', 0)
				->orderBy('name', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
		}
		return $users;
	}
	
	/* Function name: getNames
	 * Input: -
	 * Output: array of {id, name, verified}
	 *
	 * This function returns the
	 * registered, but not accepted
	 * or rejected users' following data:
	 * {id, name, verified}.
	 */
	public function getNames(){
		try{
			$names = DB::table('users')
				->join('registrations', 'registrations.user_id', '=', 'users.id')
				->select('id', 'name', 'verified')
				->where('registered', '=', 0)
				->where('id', '!=', 0)
				->orderBy('name', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$names = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users', joined to 'registrations' was not successful! ".$ex->getMessage());
		}
		return $names;
	}
	
	/* Function name: setRegistrationUserById
	 * Input: $userId (integer) - the user's identifier
	 * Output: -
	 *
	 * This function sets the private
	 * variable $registrationUser to the
	 * requested registration user.
	 */
	public function setRegistrationUserById($userId){
		try{
			$user = DB::table('users')
				->join('registrations', 'registrations.user_id', '=', 'users.id')
				->where('users.registered', '=', 0)
				->where('users.id', '=', $userId)
				->first();
		}catch(\Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users', joined to 'registrations' was not successful! ".$ex->getMessage());
		}
		$this->registrationUser = $user;
	}
	
	/* Function name: getRegistrationByCode
	 * Input: $code (text) - registration code
	 * Output: Registration
	 *
	 * This function returns the
	 * requested registration.
	 */
	public function getRegistrationByCode($code){
		try{
			$user = DB::table('registrations')
				->where('code', 'LIKE', $code)
				->first();
		}catch(\Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'registrations' was not successful! ".$ex->getMessage());
		}
		return $user;
	}
	
	/* Function name: verify
	 * Input: $code (text) - registration code
	 * Output: integer (error code)
	 *
	 * This function tries to verify
	 * the registration user's e-mail
	 * address based on the verification code.
	 */
	public function verify($code){
		$errorCode = 0;
		try{
			DB::table('registrations')
				->where('code', 'LIKE', $code)
				->update([
					'verified' => 1,
					'verification_date' => Carbon::now()
				]);
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'registrations' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
	/* Function name: getNotVerifiedUserData
	 * Input: $username (text) - username
	 * Output: User
	 *
	 * This function returns the requested
	 * registered, but not accepted
	 * or rejected user.
	 */
	public function getNotVerifiedUserData($username){
		try{
			$user = DB::table('users')
				->where('username', 'LIKE', $username)
				->where('registered', '=', 0)
				->first();
		}catch(\Illuminate\Database\QueryException $e){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
		}
		return $user;
	}
	
	/* Function name: addCode
	 * Input: 	$userId (integer) - user's identifier
	 * 			$code (text) - registration code
	 * Output: -
	 *
	 * This function returns the requested
	 * registered, but not accepted
	 * or rejected user.
	 * 
	 * THROWS EXCEPTIONS!
	 */
	public function addCode($userId, $code){
		DB::table('registrations')
			->insert([
				'user_id' => $userId,
				'code' => $code,
			]);
	}
	
	
	/* Function name: insertGuestData
	 * Input: 	$username (text) - user's username
	 * 			$password (text) - user's password
	 * 			$email (text) - user's e-mail address
	 * 			$name  (text) - user's name
	 * 			$country (text) - user's country (address)
	 * 			$shire  (text) - user's shire (address)
	 * 			$postalCode (text) - user's postal code (address)
	 * 			$address (text) - user's address (address)
	 * 			$city (text) - user's city (address)
	 * 			$reasonOfRegistration (text) - user's reason of registration
	 * 			$phoneNumber (text) - user's phone number
	 * 			$defaultLanguage (text) - user's default language
	 * Output: -
	 *
	 * This function registers a guest user.
	 */
	public function insertGuestData($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $reasonOfRegistration, $phoneNumber, $defaultLanguage){
		try{
			DB::table('users')
				->insert([
					'username' => $username,
		            'password' => password_hash($password, PASSWORD_DEFAULT),
		            'email' => $email,
		            'name' => $name,
		            'registration_date' => Carbon::now()->toDateTimeString(),
					'country' => $country,
					'shire' => $shire,
					'postalcode' => $postalCode,
					'address' => $address,
					'city' => $city,
					'reason' => $reasonOfRegistration,
					'phone' => $phoneNumber,
					'language' => $defaultLanguage,
				]);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'users' was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: insertCollegistData
	 * Input: 	$username (text) - user's username
	 * 			$password (text) - user's password
	 * 			$email (text) - user's e-mail address
	 * 			$name  (text) - user's name
	 * 			$country (text) - user's country (address)
	 * 			$shire  (text) - user's shire (address)
	 * 			$postalCode (text) - user's postal code (address)
	 * 			$address (text) - user's address (address)
	 * 			$city (text) - user's city (address)
	 * 			$phoneNumber (text) - user's phone number
	 * 			$defaultLanguage (text) - user's default language
	 * 			$cityOfBirth (text) - user's place of birth
	 * 			$dateOfBirth (datetime) - user's date of birth
	 * 			$nameOfMother (text) - user's mother's name
	 * 			$yearOfLeavingExam (integer) - user's year of the leaving exam
	 * 			$highSchool (text) - user's name of highschool
	 * 			$neptun (text) - user's neptun code
	 * 			$applicationYear (integer) - user's application year to the dormitory
	 * 			$faculty (integer) - user's faculty code
	 * 			$workshop (integer) - user's workshop code
	 * Output: -
	 *
	 * This function registers a guest user.
	 */
	public function insertCollegistData($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $phoneNumber, $defaultLanguage, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculty, $workshop){
		try{
			DB::table('users')
				->insert([
					'username' => $username,
		            'password' => password_hash($password, PASSWORD_DEFAULT),
		            'email' => $email,
		            'name' => $name,
		            'registration_date' => Carbon::now()->toDateTimeString(),
					'country' => $country,
					'shire' => $shire,
					'postalcode' => $postalCode,
					'address' => $address,
					'city' => $city,
					'reason' => null,
					'phone' => $phoneNumber,
					'language' => $defaultLanguage,
					'city_of_birth' => $cityOfBirth,
					'date_of_birth' => substr(str_replace('.', '-', $dateOfBirth), 0, -1),
					'name_of_mother' => $nameOfMother,
					'year_of_leaving_exam' => $yearOfLeavingExam,
					'high_school' => $highSchool,
					'neptun' => $neptun,
					'from_year' => $applicationYear,
					'faculty' => $faculty,
					'workshop' => $workshop,
				]);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'users' was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: addUserDefaultPermissions
	 * Input: 	$userType (text) - user's type
	 * 			$userId (integer) - user's identifier
	 * Output: integer (error code)
	 *
	 * This function gives the registered user
	 * the default permissions based on the user
	 * type.
	 * 
	 * THROWS EXCEPTIONS!
	 */
	public function addUserDefaultPermissions($userType, $userId){
		//get the default permissions
		$permissions = DB::table('default_permissions')
			->where('registration_type', 'LIKE', $userType)
			->orderBy('id', 'asc')
			->get()
			->toArray();
		//set the user permissions
		foreach($permissions as $permission){
			DB::table('user_permissions')
				->insert([
					'user_id' => $userId,
					'permission_id' => $permission->permission
				]);
		}
	}
	
	/* Function name: reject
	 * Input: $userId (integer) - user's identifier
	 * Output: integer (error code)
	 *
	 * This function rejects a user
	 * registration.
	 */
	public function reject($userId){
		$errorCode = 0;
		try{
			DB::table('users')
				->where('id', '=', $userId)
				->where('registered', '=', 0)
				->where('id', '!=', 0)
				->delete();
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'users' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
	/* Function name: acceptGuest
	 * Input: 	$userId (integer) - user's identifier
	 * 			$country (text) - user's country (address)
	 * 			$shire  (text) - user's shire (address)
	 * 			$postalCode (text) - user's postal code (address)
	 * 			$address (text) - user's address (address)
	 * 			$city (text) - user's city (address)
	 * 			$phone (text) - user's phone number
	 * 			$reason (text) - user's reason of registration
	 * Output: integer (error code)
	 *
	 * This function accepts a guest user registration.
	 * 
	 * Error codes:
	 * 		0 - success
	 * 		1 - user status code exception
	 * 		2 - user registration update exception
	 */
	public function acceptGuest($userId, $country, $shire, $postalCode, $address, $city, $phone, $reason){
		$errorCode = 0;
		try{
			$status = DB::table('user_status_codes')
				->where('status_name', 'LIKE', 'visitor')
				->first();
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'user_status_codes' was not successful! ".$ex->getMessage());
		}
		if($errorCode === 0){
			try{
				DB::table('users')
					->where('id', '=', $userId)
					->update([
						'registered' => 1,
						'country' => $country,
						'shire' => $shire,
						'postalcode' => $postalCode,
						'address' => $address,
						'city' => $city,
						'phone' => $phone,
						'reason' => $reason,
						'status' => $status->id
					]);
			}catch(\Exception $ex){
				$errorCode = 2;
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'users' was not successful! ".$ex->getMessage());
			}
		}
		return $errorCode;
	}
	
	/* Function name: acceptGuest
	 * Input: 	$userId (integer) - user's identifier
	 * 			$country (text) - user's country (address)
	 * 			$shire  (text) - user's shire (address)
	 * 			$postalCode (text) - user's postal code (address)
	 * 			$address (text) - user's address (address)
	 * 			$city (text) - user's city (address)
	 * 			$phone (text) - user's phone number
	 * 			$cityOfBirth (text) - user's place of birth
	 * 			$dateOfBirth (datetime) - user's date of birth
	 * 			$nameOfMother (text) - user's mother's name
	 * 			$yearOfLeavingExam (integer) - user's year of the leaving exam
	 * 			$highSchool (text) - user's name of highschool
	 * 			$neptun (text) - user's neptun code
	 * 			$applicationYear (integer) - user's application year to the dormitory
	 * 			$faculty (integer) - user's faculty code
	 * 			$workshop (integer) - user's workshop code
	 * Output: integer (error code)
	 *
	 * This function accepts a collegist user registration.
	 */
	public function acceptCollegist($userId, $country, $shire, $postalCode, $address, $city, $phone, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptunCode, $applicationYear, $faculty, $workshop){
		$errorCode = 0;
		try{
			DB::table('users')
				->where('id', '=', $userId)
				->update([
					'registered' => 1,
					'country' => $country,
					'shire' => $shire,
					'postalcode' => $postalCode,
					'address' => $address,
					'city' => $city,
					'phone' => $phone,
					'city_of_birth' => $cityOfBirth,
					'name_of_mother' => $nameOfMother,
					'date_of_birth' => $dateOfBirth,
					'year_of_leaving_exam' => $yearOfLeavingExam,
					'high_school' => $highSchool,
					'neptun' => $neptunCode,
					'from_year' => $applicationYear,
					'faculty' => $faculty,
					'workshop' => $workshop,
				]);
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'users' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
// PRIVATE FUNCTIONS

}
