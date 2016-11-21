<?php

namespace App\Classes;

use Carbon\Carbon;
use App\Classes\Data\User;
use App\Persistence\P_General;
use App\Persistence\P_User;
use App\Exceptions\DatabaseException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\ValueMismatchException;

/** Class name: Notifications
 *
 * This class handles the user notifications.
 *
 * Functionality:
 * 		- user notification
 * 		- user group notification based on group
 * 		- user notification from server
 * 
 * Functions that can throw exceptions:
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Notifications{

// PUBLIC FUNCTIONS
	
	/**Function name: notify
	 *
	 * This function sends a notification to a target user
	 * from a user with the given subject and text.
	 *
	 * The route is used to redirect the user to a page
	 * where the user can solve see the source of the
	 * notification or can solve the problem related to
	 * the notification.
	 * 
	 * @param User $from - sender user
	 * @param int $toId - receiver user's id
	 * @param text $subject - subject of the notification
	 * @param text $message - message of the notification
	 * @param text $route - source route of the notification
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function notify(User $from, $toId, $subject, $message, $route){
		$max_count = 100;
	
		if($from !== null && $toId !== null && $subject !== null && $message !== null){
			try{
				P_General::insertNewNotification($toId, $from->id(), $subject, $message, Carbon::now()->toDateTimeString(), false, $route);
			}catch(\Exception $ex){
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'notifications' was not successful! ".$ex->getMessage());
			}
				
			//maintain
			try{
				$count = P_General::getNotificationCountForUser($toId);
			}catch(\Exception $ex){
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'notifications' was not successful! ".$ex->getMessage());
				$count = null;
			}
			if($count !== null && $count > $max_count){
				try{
					$notifications = P_General::getOldestNonAdminNotificationsForUser($toId, $count - $max_count);
				}catch(Exception $ex){
					$notifications = [];
					Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'notifications' was not successful! ".$ex->getMessage());
				}
				foreach($notifications as $notify){
					try{
						P_General::deleteNotification($notify->id);
					}catch(Exception $ex){
						Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'notifications' was not successful! ".$ex->getMessage());
					}
				}
			}
		}
	}
	
	/** Function name: notifyAdmin
	 *
	 * This function sends a notification to a group of users
	 * from a user with the given subject and text. The group
	 * is given by a permission name. Users with the given
	 * permission will get the notification.
	 *
	 * The route is used to redirect the user to a page
	 * where the user can solve see the source of the
	 * notification or can solve the problem related to
	 * the notification.
	 * 
	 * @param User $from - sender user
	 * @param text $adminPermission - permission name
	 * @param text $subject - subject of the notification
	 * @param text $message - content of the notification
	 * @param text $route - route to the source page
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function notifyAdmin(User $from, $adminPermission, $subject, $message, $route){
		if($from !== null && $adminPermission !== null && $subject !== null && $message !== null){
			try{
				$admins = P_User::getUsersWithPermission($adminPermission);
			}catch(Exception $ex){
				$admins = [];
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'notifications' joined to 'user_permissions' and 'permissions' was not successful! ".$ex->getMessage());
			}
			foreach($admins as $admin){
				try{
					P_General::insertNewNotification($admin->id(), $from->id(), $subject, $message, Carbon::now()->toDateTimeString(), true, $route);
				}catch(Exception $ex){
					Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'notifications' was not successful! ".$ex->getMessage());
				}
			}
		}
	}
	
	/** Function name: notifyAdminFromServer
	 *
	 * This function sends a notification to a group of users
	 * from the server with the given subject and text. The group
	 * is given by a permission name. Users with the given
	 * permission will get the notification.
	 *
	 * The route is used to redirect the user to a page
	 * where the user can solve see the source of the
	 * notification or can solve the problem related to
	 * the notification.
	 * 
	 * @param text $adminPermission - permission name
	 * @param text $subject - subject of the notification
	 * @param text $message - content of the notification
	 * @param text $route - route to the source page
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function notifyAdminFromServer($adminPermission, $subject, $message, $route){
		Notifications::notifyAdmin(\App\Classes\Layout\User::getUserData(0), $adminPermission, $subject, $message, $route);
	}
	
	/** Function name: getNotifications
	 *
	 * This function returns the requested
	 * user's notifications.
	 * 
	 * @param int $userId - requested user identifier
	 * @return array of the user's notifications
	 * 
	 * @throws UserNotFoundException when the provided user does not exist.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getNotifications($userId){
		\App\Classes\Layout\User::getUserData($userId); //check the user
		try{
			$ret = P_General::getNotificationsForUser($userId);
		}catch(Exception $ex){
			$ret = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'notifications' joined to 'users' was not successful! ".$ex->getMessage());
		}
		return $ret;
	}
	
	/** Function name: getUnreadNotificationCount
	 *
	 * This function returns the count of the
	 * requested user's unread notifications.
	 * 
	 * @param int $userId - requested user
	 * @return int - count of unseen notifications
	 * 
	 * @throws UserNotFoundException when the provided user does not exist.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getUnreadNotificationCount($userId){
		\App\Classes\Layout\User::getUserData($userId); //check the user
		try{
			$count = P_General::getUnseenNotificationCountForUser($userId);
		}catch(Exception $ex){
			$count = 0;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'notifications' was not successful! ".$ex->getMessage());
		}
		return $count;
	}
	
	/** Function name: get
	 *
	 * This function returns a notification based
	 * on the user and the notification identifier.
	 * 
	 * @param int $notificationId - identifier of a notification
	 * @param int $userId - requested user
	 * @return int - count of unseen notifications
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function get($notificationId, $userId){
		try{
			$notification = P_General::getNotification($notificationId, $userId);
		}catch(Exception $ex){
			$notification = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'notifications' was not successful! ".$ex->getMessage());
		}
		return $notification;
	}
	
	/** Function name: setRead
	 *
	 * This function sets the status of a
	 * notifiation as read.
	 * 
	 * @param int $notificationId - identifier of a notification
	 * 
	 * @throws DatabaseException when the update is not successful!
	 * @throws ValueMismatchException when the notification id is null!
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function setRead($notificationId){
		if($notificationId === null){
			throw new ValueMismatchException("The id must not be null!");
		}
		try{
			P_General::setNotificationAsSeen($notificationId);
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'notifications' was not successful! ".$ex->getMessage());
			throw new DatabaseException("Could not set the notification as seen!");
		}
	}
	
// PRIVATE FUNCTIONS
	
}
