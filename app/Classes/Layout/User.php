<?php

namespace App\Classes\Layout;

use App\Classes\Layout\Permissions;
use App\Classes\Notifications;
use App\Persistence\P_User;
use App\Exceptions\UserNotFoundException;

/** Class name: User
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
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class User{
	
// PRIVATE DATA
	
	private $user;
	private $permissions;
	private $notifications;
	private $unreadNotificationCount;

// PUBLIC FUNCTIONS
	
	/** Function name: __construct
	 *
	 * The constructor for the User class.
	 * 
	 * @param int $userId - user's identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct($userId){
		try{
			$this->user = $this->getUserData($userId);
		}catch(\Exception $ex){
			$this->user = null;
		}
		$tmpPermissions = new Permissions();
		$this->permissions = $tmpPermissions->getForUser($userId);
		try{
			$this->notifications = Notifications::getNotifications($userId);
			$this->unreadNotificationCount = Notifications::getUnreadNotificationCount($userId);
		}catch(\Exception $ex){
			$this->notifications = null;
			$this->unreadNotificationCount = 0;
		}
	}
	
	/** Function name: user
	 *
	 * Getter function for user.
	 * 
	 * @return User
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function user(){
		return $this->user;
	}
	
	/** Function name: users
	 *
	 * Getter function for users.
	 * 
	 * @return array of Users
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function users(){
		try{
			$users = P_User::getUsers();
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
		}
		return $users;
	}
	
	/** Function name: permissions
	 *
	 * Getter function for permissions.
	 * 
	 * @return array of Permissions
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function permissions(){
		return $this->permissions;
	}
	
	/** Function name: notificationCount
	 *
	 * Getter function for notification count.
	 * 
	 * @return int - count
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function notificationCount(){
		return count($this->notifications);
	}
	
	/** Function name: unreadNotificationCount
	 *
	 * Getter function for unread notifications.
	 *
	 * @return array of Notifications
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function unreadNotificationCount(){
		return $this->unreadNotificationCount;
	}
	
	/** Function name: notifications
	 *
	 * This function returns the notifications.
	 * 
	 * @param int $from - first notification
	 * @param int $count - count of notifications
	 * @return array of Notifications
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function notifications($from = 0, $count = 5){
		if($this->notifications === []){
			return [];
		}else if($from < 0 || count($this->notifications) < $from || $count < 0){
			return [];
		}else if(count($this->notifications) <= $from + $count){
			return array_slice($this->notifications, $from, count($this->notifications) - $from);
		}else{
			return array_slice($this->notifications, $from, $count);
		}
	}
	
	/** Function name: usersAllData
	 *
	 * This function returns a part
	 * of the users from the first
	 * requested user.
	 * 
	 * @param int $from - identifier of first notification
	 * @param int $count - count of notifications
	 * @return array of Users
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function usersAllData($from = 0, $count = 50){
		try{
			$users = P_User::getUsers($from, $count);
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users', joined to 'user_status_codes' was not successful! ".$ex->getMessage());
		}
		return $users === null ? [] : $users;
	}
	
	/** Function name: permitted
	 *
	 * This function indicates whether
	 * the current user has a the requested
	 * permission or not.
	 * 
	 * @param text $what - permission short identifier
	 * @return bool - permitted or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function permitted($what){
		$i = 0;
		while($i < count($this->permissions) && $this->permissions[$i]->permission_name != $what){
			$i++;
		}
		return $i < count($this->permissions);
	}
	
	/** Function name: getUserData
	 *
	 * This function returns the
	 * requested user's data.
	 * 
	 * @param int $userId - user's identifier
	 * @return User|null - user data
	 * 
	 * @throws UserNotFoundException when the user cannot be found or a database error occurs.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getUserData($userId){
		try{
			$user = P_User::getUserById($userId);
		}catch(\Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
		}
		if($user === null){
			throw new UserNotFoundException();
		}
		return $user;
	}
	
	/** Function name: getUserDataByUsername
	 *
	 * This function returns the requested user's full data.
	 * Not only the user table, but it joins a lot more table
	 * and gives all the informations stored in the database
	 * about the target. (Excluded the modules.)
	 * 
	 * @param text $username - user's name
	 * @return User|null the requested user's data
	 * 
	 * @throws UserNotFoundException when the user cannot be found or a database error occurs.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getUserDataByUsername($username){
		try{
			$user = P_User::getUserByUsername($username);
		}catch(\Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
		}
		if($user === null){
			throw new UserNotFoundException();
		}
		return $user;
	}
	
	/** Function name: saveUserLanguage
	 *
	 * This function updates the user default language.
	 * 
	 * @param text $lang - language identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function saveUserLanguage($lang){
		try{
			P_User::updateUserLanguage($this->user->id, $lang);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'users' was not successful! ".$ex->getMessage());
		}
	}
	
// PRIVATE FUNCTIONS
	
}
