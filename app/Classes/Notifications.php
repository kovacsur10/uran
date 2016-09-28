<?php

namespace App\Classes;

use Carbon\Carbon;
use App\Classes\Layout\User;
use DB;

class Notifications{

	/* Function name: getRoomId
	 * Input: 	$from (User) - sender user
	 * 			$toId (int) - receiver user
	 * 			$subject (text) - subject of the notification
	 * 			$message (text) - content of the notification
	 * 			$route (text) - route to the source page
	 * Output: -
	 *
	 * This function sends a notification to a target user
	 * from a user with the given subject and text.
	 *
	 * The route is used to redirect the user to a page
	 * where the user can solve see the source of the
	 * notification or can solve the problem related to
	 * the notification.
	 */
	public static function notify(User $from, $toId, $subject, $message, $route){
		$max_count = 100;
	
		if($from != null && $toId != null && $subject != null && $message != null){
			if($route == null){
				DB::table('notifications')
				->insert([
						'user_id' => $toId,
						'subject' => $subject,
						'message' => $message,
						'from' => $from->user()->id,
						'time' => Carbon::now()->toDateTimeString()
				]);
			}else{
				DB::table('notifications')
				->insert([
						'user_id' => $toId,
						'subject' => $subject,
						'message' => $message,
						'from' => $from->user()->id,
						'route' => $route,
						'time' => Carbon::now()->toDateTimeString()
				]);
			}
				
			//maintain
			$count = DB::table('notifications')
			->where('user_id', '=', $toId)
			->count();
			if($count != null && $count > $max_count){
				$notifications = DB::table('notifications')
				->where('user_id', '=', $toId)
				->where('admin', '=', 'false')
				->orderBy('id', 'asc')
				->take($count - $max_count)
				->get();
				foreach($notifications as $notify){
					DB::table('notifications')
					->where('id', '=', $notify->id)
					->delete();
				}
			}
		}
	}
	
	/* Function name: notifyAdmin
	 * Input: 	$from (User) - sender user
	 * 			$adminPermission (text) - permission name
	 * 			$subject (text) - subject of the notification
	 * 			$message (text) - content of the notification
	 * 			$route (text) - route to the source page
	 * Output: -
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
	 */
	public static function notifyAdmin(User $from, $adminPermission, $subject, $message, $route){
		if($from !== null && $adminPermission !== null && $subject !== null && $message !== null){
			$admins = DB::table('users')
			->join('user_permissions', 'user_permissions.user_id', '=', 'users.id')
			->join('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
			->where('permissions.permission_name', 'LIKE', $adminPermission)
			->select('users.id as id')
			->get();
			foreach($admins as $admin){
				if($route === null){
					DB::table('notifications')
					->insert([
							'user_id' => $admin->id,
							'subject' => $subject,
							'message' => $message,
							'from' => $from->user()->id,
							'time' => Carbon::now()->toDateTimeString(),
							'admin' => 'true'
					]);
				}else{
					DB::table('notifications')
					->insert([
							'user_id' => $admin->id,
							'subject' => $subject,
							'message' => $message,
							'from' => $from->user()->id,
							'route' => $route,
							'time' => Carbon::now()->toDateTimeString(),
							'admin' => 'true'
					]);
				}
			}
		}
	}
	
	/* Function name: notifyAdmin
	 * Input: 	$adminPermission (text) - permission name
	 * 			$subject (text) - subject of the notification
	 * 			$message (text) - content of the notification
	 * 			$route (text) - route to the source page
	 * Output: -
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
	 */
	public static function notifyAdminFromServer($adminPermission, $subject, $message, $route){
		Notifications::notifyAdmin(new User(0), $adminPermission, $subject, $message, $route);
	}
	
	/* Function name: getNotifications
	 * Input: 	$from (User) - sender user
	 * 			$toId (int) - receiver user
	 * 			$subject (text) - subject of the notification
	 * 			$message (text) - content of the notification
	 * 			$route (text) - route to the source page
	 * Output: -
	 *
	 * This function sends a notification to a target user
	 * from a user with the given subject and text.
	 *
	 * The route is used to redirect the user to a page
	 * where the user can solve see the source of the
	 * notification or can solve the problem related to
	 * the notification.
	 */
	public static function getNotifications($userId){
		$ret = DB::table('notifications')
			->join('users', 'users.id', '=', 'notifications.from')
			->select('users.name as name', 'users.username as username', 'notifications.id as id', 'notifications.subject as subject', 'notifications.message as message', 'notifications.time as time', 'notifications.seen as seen')
			->where('user_id', '=', $userId)
			->orderBy('id', 'desc')
			->get();
		return $ret->toArray();
	}
	
	public static function getUnseenNotificationCount($userId){
		return DB::table('notifications')
			->where('user_id', '=', $userId)
			->where('seen', '=', 'false')
			->count('id');
	}
	
	public static function get($notificationId, $userId){
		return DB::table('notifications')
			->where('id', '=', $notificationId)
			->where('user_id', '=', $userId)
			->first();
	}
	
	public static function setSeen($notificationId){
		DB::table('notifications')
			->where('id', '=', $notificationId)
			->update(['seen' => 'true']);
	}
	
}
