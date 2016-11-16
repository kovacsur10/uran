<?php

namespace App\Persistence;

use DB;
use App\Classes\Data\StatusCode;

/** Class name: P_User
 *
 * This class is the database persistence layer class
 * for the user related tables.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class P_User{
	
	/** Function name: getUsersWithPermission
	 *
	 * This function returns those users, who
	 * have the requested permission.
	 *
	 * @param text $permissionName - permission text identifier
	 * @return array of users
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUsersWithPermission($permissionName){
		return DB::table('users')
			->join('user_permissions', 'user_permissions.user_id', '=', 'users.id')
			->join('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
			->where('permissions.permission_name', 'LIKE', $permissionName)
			->where('users.registered', '=', true)
			->select('users.id as id', 'users.name as name', 'users.username as username')
			->get()
			->toArray();
	}
	
	/** Function name: getUserPermissions
	 *
	 * This function returns the available permissions
	 * for the requested user.
	 *
	 * @param int $userId - user's identifier
	 * @return array of Permissions
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUserPermissions($userId){
		return DB::table('permissions')
			->join('user_permissions', 'permissions.id', '=', 'user_permissions.permission_id')
			->select('permissions.id as id', 'permission_name', 'permissions.description as description')
			->where('user_permissions.user_id', '=', $userId)
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
	/** Function name: removePermissionsForUser
	 *
	 * This function removes all of the permissions
	 * possessed by the requested user.
	 *
	 * @param int $userId - user's identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function removePermissionsForUser($userId){
		DB::table('user_permissions')
			->where('user_id', '=', $userId)
			->delete();
	}
	
	/** Function name: addPermissionForUser
	 *
	 * This function adds a new permissions
	 * for the requested user.
	 *
	 * @param int $userId - user's identifier
	 * @param int $permissionId - permission identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addPermissionForUser($userId, $permissionId){
		DB::table('user_permissions')
			->insert([
					'user_id' => $userId,
					'permission_id' => $permissionId
			]);
	}
	
	/** Function name: updateUserLoginTime
	 * 
	 * This function updates the user's
	 * last login time to the requested value.
	 * 
	 * @param text $username - user text identifier
	 * @param datetime $datetime - login time
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function updateUserLoginTime($username, $datetime){
		DB::table('users')
			->where('username', 'LIKE', $username)
			->update([
				'last_online' => $datetime
			]);
	}
	
	/** Function name: updateUserPassword
	 * 
	 * This function updates the requested user's
	 * password for the given value.
	 * 
	 * @param text $username - user text identifier
	 * @param text $password - new password
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function updateUserPassword($username, $password){
		DB::table('users')
			->where('username', 'LIKE', $username)
			->update([
				'password' => $password
			]);
	}
	
	/** Function name: updateUserLanguage
	 *
	 * This function updates the requested user's
	 * default language.
	 *
	 * @param text $userId - user's identifier
	 * @param text $langId - language identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function updateUserLanguage($userId, $langId){
		DB::table('users')
			->where('id', '=', $userId)
			->update([
					'language' => $langId
			]);
	}
	
	/** Function name: getUserById
	 *
	 * This function returns the user, who
	 * has the requested user identifier.
	 *
	 * @param int $userId - user's identifier
	 * @return User|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUserById($userId){
		return $user = DB::table('users')
			->leftJoin('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->leftJoin('workshops', 'workshops.id', '=', 'users.workshop')
			->leftJoin('faculties', 'faculties.id', '=', 'users.faculty')
			->where('users.id', '=', $userId)
			/*->when($userId !== 0, function($query){
				return $query->where('users.registered', '=', true);
			})*/
			->select('users.id as id', 'users.username as username', 'users.email as email', 'users.registration_date as registration_date', 'users.name as name', 'users.country as country', 'users.shire as shire', 'users.city as city', 'users.postalcode as postalcode', 'users.address as address', 'users.phone as phone', 'users.reason as reason', 'users.neptun as neptun', 'users.city_of_birth as city_of_birth', 'users.date_of_birth as date_of_birth', 'users.name_of_mother as name_of_mother', 'users.high_school as high_school', 'users.year_of_leaving_exam as year_of_leaving_exam', 'user_status_codes.status_name as status', 'user_status_codes.id as status_id', 'workshops.name as workshop', 'workshops.id as workshop_id', 'faculties.name as faculty', 'faculties.id as faculty_id', 'users.from_year as admission_year')
			->first();
	}
	
	/** Function name: getUserByUsername
	 *
	 * This function returns the user, who
	 * has the requested username as text
	 * identifier value.
	 * 
	 * @param text $username - user's text identifier
	 * @return User|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUserByUsername($username){
		return DB::table('users')
			->leftJoin('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->leftJoin('workshops', 'workshops.id', '=', 'users.workshop')
			->leftJoin('faculties', 'faculties.id', '=', 'users.faculty')
			->where('users.username', 'LIKE', $username)
			->where('users.registered', '=', 1)
			->select('users.id as id', 'users.username as username', 'users.password as password', 'users.email as email', 'users.registration_date as registration_date', 'users.name as name', 'users.country as country', 'users.shire as shire', 'users.city as city', 'users.postalcode as postalcode', 'users.address as address', 'users.phone as phone', 'users.reason as reason', 'users.neptun as neptun', 'users.city_of_birth as city_of_birth', 'users.date_of_birth as date_of_birth', 'users.name_of_mother as name_of_mother', 'users.high_school as high_school', 'users.year_of_leaving_exam as year_of_leaving_exam', 'user_status_codes.status_name as status', 'user_status_codes.id as status_id', 'workshops.name as workshop', 'workshops.id as workshop_id', 'faculties.name as faculty', 'faculties.id as faculty_id', 'users.from_year as admission_year', 'users.language as language')
			->first();
	}

	/** Function name: getUsers
	 * 
	 * This function returns user data. First
	 * it skip the requested number of user, then
	 * it returns maximum the requested number of
	 * users.
	 * 
	 * @param int $skipped - first n skipped user
	 * @param int $taken - maximum returned users
	 * @return array of Users
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUsers($skipped = 0, $taken = -1){
		return DB::table('users')
			->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->where('registered', '=', 1)
			->select('*', 'users.id as id')
			->orderBy('name', 'asc')
			->skip($skipped)
			->when($taken > -1, function($query) use ($taken){
				return $query->take($taken);
			})
			->get()
			->toArray();
	}
	
	/** Function name: getStatusCodes
	 *
	 * This function returns the existing status
	 * codes of a user from the database.
	 *
	 * @return array of StatusCode
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getStatusCodes(){
		$getStatusCodes = DB::table('user_status_codes')
			->orderBy('id', 'asc')
			->get();
		$statusCodes = [];
		foreach($getStatusCodes as $statusCode){
			array_push($statusCodes, new StatusCode($statusCode->id, $statusCode->status_name));
		}
		return $statusCodes;
	}
	/** Function name: getStatusCodeByName
	 *
	 * This function returns the requested status.
	 *
	 * @param text $statusName - status text identifier
	 * @return StatusCode|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getStatusCodeByName($statusName){
		return DB::table('user_status_codes')
			->where('status_name', 'LIKE', $statusName)
			->first();
	}
	
//REGISTRATION USER

	/** Function name: getRegistrationUserById
	 *
	 * This function returns the requested registration
	 * user.
	 *
	 * @param int $userId - user's identifier
	 * @return User|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getRegistrationUserById($userId){
		return DB::table('users')
			->join('registrations', 'registrations.user_id', '=', 'users.id')
			->where('users.registered', '=', false)
			->where('users.id', '=', $userId)
			->first();
	}
	
	/** Function name: getRegistrationUserByUsername
	 *
	 * This function returns the requested registration
	 * user.
	 *
	 * @param text $username - user's text identifier
	 * @return User|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getRegistrationUserByUsername($username){
		return DB::table('users')
			->where('users.registered', '=', false)
			->where('users.username', 'LIKE', $username)
			->first();
	}
	
	/** Function name: getRegistrationUsers
	 *
	 * This function returns all of the registration
	 * users.
	 *
	 * @return array of Users
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getRegistrationUsers(){
		return DB::table('users')
			->join('registrations', 'registrations.user_id', '=', 'users.id')
			->where('users.registered', '=', false)
			->where('users.id', '!=', 0)
			->orderBy('users.name', 'asc')
			->get()
			->toArray();
	}
	
	/** Function name: addRegistrationData
	 * 
	 * This function adds a new user line with
	 * the given data to the database.
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
	 * @param text $reason
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
	 * @param datetime $date
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addRegistrationData($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $reason, $phoneNumber, $defaultLanguage, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculty, $workshop, $date){
		DB::table('users')
			->insert([
					'username' => $username,
					'password' => $password,
					'email' => $email,
					'name' => $name,
					'registration_date' => $date,
					'country' => $country,
					'shire' => $shire,
					'postalcode' => $postalCode,
					'address' => $address,
					'city' => $city,
					'reason' => $reason,
					'phone' => $phoneNumber,
					'language' => $defaultLanguage,
					'city_of_birth' => $cityOfBirth,
					'date_of_birth' => $dateOfBirth,
					'name_of_mother' => $nameOfMother,
					'year_of_leaving_exam' => $yearOfLeavingExam,
					'high_school' => $highSchool,
					'neptun' => $neptun,
					'from_year' => $applicationYear,
					'faculty' => $faculty,
					'workshop' => $workshop,
			]);
	}
	
	/** Function name: addRegistrationCodeEntry
	 * 
	 * This function creates an entry with the
	 * requested code and user id to the registrations
	 * database table.
	 * 
	 * @param int $userId - user's identifier
	 * @param text $code - registration code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addRegistrationCodeEntry($userId, $code){
		DB::table('registrations')
			->insert([
					'user_id' => $userId,
					'code' => $code,
			]);
	}

	/** Function name: verifyRegistrationUser
	 *
	 * This function returns all of the registration
	 * users.
	 *
	 * @param text $code - verification code
	 * @param datetime $time - verification time
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function verifyRegistrationUser($code, $time){
		DB::table('registrations')
			->where('code', 'LIKE', $code)
			->update([
					'verified' => true,
					'verification_date' => $time
			]);
	}
	
	/** Function name: promoteRegistrationUserToUser
	 * 
	 * This function sets the user registration flag to
	 * 'true' and updates the valid data.
	 * 
	 * @param int $userId
	 * @param text $country
	 * @param text $shire
	 * @param text $postalCode
	 * @param text $address
	 * @param text $city
	 * @param text $phone
	 * @param text $reason
	 * @param text $cityOfBirth
	 * @param datetime $dateOfBirth
	 * @param text $nameOfMother
	 * @param int $yearOfLeavingExam
	 * @param text $highSchool
	 * @param text $neptunCode
	 * @param int $applicationYear
	 * @param int $faculty
	 * @param int $workshop
	 * @param int $status
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function promoteRegistrationUserToUser($userId, $country, $shire, $postalCode, $address, $city, $phone, $reason, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptunCode, $applicationYear, $faculty, $workshop, $status){
		DB::table('users')
			->where('id', '=', $userId)
			->where('registered', '=', false)
			->update([
					'registered' => true,
					'country' => $country,
					'shire' => $shire,
					'postalcode' => $postalCode,
					'address' => $address,
					'city' => $city,
					'phone' => $phone,
					'reason' => $reason,
					'city_of_birth' => $cityOfBirth,
					'name_of_mother' => $nameOfMother,
					'date_of_birth' => $dateOfBirth,
					'year_of_leaving_exam' => $yearOfLeavingExam,
					'high_school' => $highSchool,
					'neptun' => $neptunCode,
					'from_year' => $applicationYear,
					'faculty' => $faculty,
					'workshop' => $workshop,
					'status' => $status,
			]);
	}
	
	/** Function name: removeRegistrationUser
	 *
	 * This function removes the requested 
	 * registration user.
	 *
	 * @param int $userId - user's identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function removeRegistrationUser($userId){
		DB::table('users')
			->where('id', '=', $userId)
			->where('registered', '=', false)
			->where('id', '!=', 0)
			->delete();
	}
}
