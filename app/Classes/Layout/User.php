<?php

namespace App\Classes\Layout;

use DB;
use App\Classes\Layout\Permissions;
use App\Classes\Notifications;

/* Class name: User
 *
 * This class handles the default
 * user database functionality.
 *
 * Functionality:
 * 		- get user data
 * 		- get user permissions
 * 		- get all user's data
 * 		- get notifications for user
 * 
 * Functions that can throw exceptions:
 */
class User{
	
// PRIVATE DATA
	
	private $user;
	private $permissions;
	private $notifications;
	private $unreadNotificationCount;

// PUBLIC FUNCTIONS
	
	/* Function name: __construct
	 * Input: $userId (integer) - identifier for user
	 * Output: -
	 *
	 * The constructor for the User class.
	 */
	public function __construct($userId){
		$this->user = $this->getUserData($userId);
		$tmpPermissions = new Permissions();
		$this->permissions = $tmpPermissions->getForUser($userId);
		$this->notifications = Notifications::getNotifications($userId);
		$this->unreadNotificationCount = Notifications::getUnreadNotificationCount($userId);
	}
	
	/* Function name: user
	 * Input: -
	 * Output: user
	 *
	 * Getter function for user.
	 */
	public function user(){
		return $this->user;
	}
	
	/* Function name: users
	 * Input: -
	 * Output: array of users
	 *
	 * Getter function for users.
	 */
	public function users(){
		try{
			$users = DB::table('users')
				->select('id', 'username', 'name')
				->where('registered', '=', 1)
				->orderBy('name', 'asc')
				->get();
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
		}
		return $users;
	}
	
	/* Function name: permissions
	 * Input: -
	 * Output: array of permissions
	 *
	 * Getter function for permissions.
	 */
	public function permissions(){
		return $this->permissions;
	}
	
	/* Function name: unreadNotificationCount
	 * Input: -
	 * Output: array of permissions
	 *
	 * Getter function for unread permissions.
	 */
	public function unreadNotificationCount(){
		return $this->unreadNotificationCount;
	}
	
	/* Function name: notificationCount
	 * Input: -
	 * Output: integer (count)
	 *
	 * Getter function for notification count.
	 */
	public function notificationCount(){
		return count($this->notifications);
	}
	
	/* Function name: latestNotifications
	 * Input: $count (integer) - count of notifications
	 * Output: array of notifications
	 *
	 * This function returns the latest
	 * notifications.
	 */
	public function latestNotifications($count = 5){
		if($this->notifications === []){
			return [];
		}else if(count($this->notifications) <= $count){
			return $this->notifications;
		}else{
			return array_slice($this->notifications, 0, $count);
		}
	}
	
	/* Function name: notifications
	 * Input:	$from (integer) - identifier of first notification
	 * 			$count (integer) - count of notifications
	 * Output: array of notifications
	 *
	 * This function returns the a part
	 * of the notifications from the first
	 * requested notification.
	 */
	public function notifications($from, $count){
		if($this->notifications === [])
			return [];
		else if($from < 0 || count($this->notifications) < $from || $count < 0)
			return [];
		else if(count($this->notifications) < $from + $count)
			return array_slice($this->notifications, $from, count($this->notifications) - $from);
		else
			return array_slice($this->notifications, $from, $count);
	}
	
	/* Function name: usersAllData
	 * Input:	$from (integer) - identifier of first notification
	 * 			$count (integer) - count of notifications
	 * Output: array of users
	 *
	 * This function returns the a part
	 * of the users from the first
	 * requested user.
	 */
	public function usersAllData($from = 0, $count = 50){
		try{
			$users = DB::table('users')
				->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
				->where('registered', '=', 1)
				->select('*', 'users.id as id')
				->orderBy('name', 'asc')
				->skip($from)
				->take($count)
				->get();
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users', joined to 'user_status_codes' was not successful! ".$ex->getMessage());
		}
		return $users === null ? [] : $users;
	}
	
	/* Function name: permitted
	 * Input: $what (text) - permission short identifier
	 * Output: bool (permitted or not)
	 *
	 * This function indicates whether
	 * the current user has a the requested
	 * permission or not.
	 */
	public function permitted($what){
		$i = 0;
		while($i < count($this->permissions) && $this->permissions[$i]->permission_name != $what){
			$i++;
		}
		return $i < count($this->permissions);
	}
	
	/* Function name: getUserData
	 * Input: $userId (integer) - user's identifier
	 * Output: User (user data)
	 *
	 * This function returns the
	 * requested user's data.
	 */
	public function getUserData($userId){
		if($userId === 0){
			try{
				$user = DB::table('users')
					->where('id', '=', 0)
					->first();
			}catch(\Exception $ex){
				$user = null;
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
			}
		}else{	
			try{
				$user = DB::table('users')
					->where('id', '=', $userId)
					->where('registered', '=', 1)
					->first();
			}catch(\Exception $ex){
				$user = null;
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
			}
		}
		return $user;
	}
	
	/* Function name: getUserData_Administration
	 * Input: $userId (integer) - user's identifier
	 * Output: User (the requested user's data)
	 *
	 * This function returns the requested user's full data.
	 * Not only the user table, but it joins a lot more table
	 * and gives all the informations stored in the database 
	 * about the target. (Excluded the modules.)
	 */
	public function getUserData_Administration($userId){
		if($userId === 0){
			return null;
		}else{	
			try{
				$user = DB::table('users')
					->where('users.id', '=', $userId)
					->where('users.registered', '=', 1)
					->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
					->join('workshops', 'workshops.id', '=', 'users.workshop')
					->join('faculties', 'faculties.id', '=', 'users.faculty')
					->select('users.id as id', 'users.username as username', 'users.email as email', 'users.registration_date as registration_date', 'users.name as name', 'users.country as country', 'users.shire as shire', 'users.city as city', 'users.postalcode as postalcode', 'users.address as address', 'users.phone as phone', 'users.reason as reason', 'users.neptun as neptun', 'users.city_of_birth as city_of_birth', 'users.date_of_birth as date_of_birth', 'users.name_of_mother as name_of_mother', 'users.high_school as high_school', 'users.year_of_leaving_exam as year_of_leaving_exam', 'user_status_codes.status_name as status', 'user_status_codes.id as status_id', 'workshops.name as workshop', 'workshops.id as workshop_id', 'faculties.name as faculty', 'faculties.id as faculty_id', 'users.from_year as admission_year')
					->first();
			}catch(\Exception $ex){
				$user = null;
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users', joined to 'user_status_codes', 'workshops', 'faculties' was not successful! ".$ex->getMessage());
			}
			return $user;
		}
	}
	
	/* Function name: getUserDataByUsername
	 * Input: $username (text) - user's name
	 * Output: User (the requested user's data)
	 *
	 * This function returns the requested user's full data.
	 * Not only the user table, but it joins a lot more table
	 * and gives all the informations stored in the database
	 * about the target. (Excluded the modules.)
	 */
	public function getUserDataByUsername($username){
		try{
			$user = DB::table('users')
				->where('username', 'LIKE', $username)
				->where('registered', '=', 1)
				->first();
		}catch(\Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
		}
		return $user;
	}
	
	/* Function name: saveUserLanguage
	 * Input: $lang (text) - language identifier
	 * Output: -
	 *
	 * This function updates the user default language.
	 */
	public function saveUserLanguage($lang){
		try{
			DB::table('users')
				->where('id', '=', $this->user->id)
				->update([
					'language' => $lang
				]);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'users' was not successful! ".$ex->getMessage());
		}
	}
	
// PRIVATE FUNCTIONS
	
}
