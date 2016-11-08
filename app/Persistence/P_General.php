<?php

namespace App\Persistence;

use DB;

class P_General{

	/** Function name: beginTransaction
	 *
	 * This function starts a new database transaction.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function beginTransaction(){
		DB::beginTransaction();
	}
	
	/** Function name: rollback
	 *
	 * This function rollback a database transaction.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function rollback(){
		DB::rollback();
	}
	
	/** Function name: commit
	 *
	 * This function commits a database transaction.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function commit(){
		DB::commit();
	}
	
	/** Function name: writeIntoLog
	 *
	 * This function writes log into the database.
	 * Severity of the log can be:
	 * 		1 - normal log
	 * 		2 - warning
	 * 		3 - error
	 *
	 * The old value and new value can be empty strings.
	 * If a value is changed according to these fields
	 * the old data can be recovered.
	 *
	 * A valid route should be added for debugging reasons.
	 * 
	 * @param text $description - short description
	 * @param text $oldValue - if changed, the old value
	 * @param text $newValue - if changed, the new value
	 * @param text $route - route to the page
	 * @param int $userId - user's identifier
	 * @param datetime $datetime - timestamp
	 * @param int $severity - severity of the log
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function writeIntoLog($description, $oldValue, $newValue, $route, $userId, $datetime, $severity){
		DB::table('logs')
			->insert([
					'description' => $description,
					'old_value' => print_r($oldValue, true),
					'new_value' => print_r($newValue, true),
					'path' => $route,
					'user_id' => $userId,
					'datetime' => $datetime,
					'type' => $severity,
			]);
	}
	
	/** Function name: insertNewNotification
	 *
	 * This function insert a new notification
	 * to the database based on the given data.
	 * 
	 * @param int $toId - sender user's identifier
	 * @param int $fromId - receiver user's identifier
	 * @param text $subject - subject of the notification
	 * @param text $message - content of the notification
	 * @param datetime $datetime - notification creation time 
	 * @param bool $admin - admin mode notification or not
	 * @param text $route - route to the source page
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function insertNewNotification($toId, $fromId, $subject, $message, $datetime, $admin, $route = null){
		DB::table('notifications')
			->insert([
					'user_id' => $toId,
					'subject' => $subject,
					'message' => $message,
					'from' => $fromId,
					'route' => $route,
					'time' => $datetime,
					'admin' => $admin
			]);
	}
	
	/** Function name: setNotificationAsSeen
	 *
	 * This function sets the 'seen' property of
	 * the notification as seen (true).
	 * 
	 * @param int $notificationId - notification identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function setNotificationAsSeen($notificationId){
		DB::table('notifications')
			->where('id', '=', $notificationId)
			->update([
				'seen' => 'true
			']);
	}
	
	/** Function name: getNotification
	 *
	 * This function returns the requested notification.
	 * If the notification is not sent to the requested
	 * user, the returned value is null.
	 * 
	 * @param int $notificationId - notification identifier
	 * @param int $userId - user's identifier
	 * @return Notification|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getNotification($notificationId, $userId){
		return DB::table('notifications')
			->where('id', '=', $notificationId)
			->where('user_id', '=', $userId)
			->first();
	}
	
	/** Function name: getNotificationCountForUser
	 *
	 * This function returns the count of the
	 * notifications for the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @return int - count
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getNotificationCountForUser($userId){
		DB::table('notifications')
		->where('user_id', '=', $userId)
		->count('id');
	}
	
	/** Function name: getUnseenNotificationCountForUser
	 *
	 * This function returns the count of the unseen
	 * notifications for the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @return int - count
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUnseenNotificationCountForUser($userId){
		DB::table('notifications')
			->where('user_id', '=', $userId)
			->where('seen', '=', 'false')
			->count('id');
	}
	
	/** Function name: getNotificationsForUser
	 *
	 * This function returns the notifications for
	 * the requested user.
	 * 
	 * @param int $userId - receiver user's identifier
	 * @return array of notifications 
	 * 		[
	 * 			id (int),
	 * 			name (text),
	 * 			username (text),
	 * 			subject (text),
	 * 			message (text),
	 * 			time (datetime),
	 * 			seen (bool)
	 * 		]
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getNotificationsForUser($userId){
		return DB::table('notifications')
			->join('users', 'users.id', '=', 'notifications.from')
			->select('notifications.id as id', 'users.name as name', 'users.username as username', 'notifications.subject as subject', 'notifications.message as message', 'notifications.time as time', 'notifications.seen as seen')
			->where('user_id', '=', $userId)
			->orderBy('id', 'desc')
			->get()
			->toArray();
	}
	
	/** Function name: getOldestNonAdminNotificationsForUser
	 *
	 * This function returns the last $count
	 * notifications for the requested user.
	 * Admin flagged notifications are not
	 * returned.
	 *
	 * @param int $userId - user's identifier
	 * @param int $count - count of notifications
	 * @return array of notifications
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getOldestNonAdminNotificationsForUser($userId, $count){
		return DB::table('notifications')
			->where('user_id', '=', $userId)
			->where('admin', '=', 'false')
			->orderBy('id', 'asc')
			->take($count)
			->get()
			->toArray();
	}
	
	/** Function name: deleteNotification
	 *
	 * This function removes the requested notification
	 * from the database.
	 * 
	 * @param int $notificationId - notification identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function deleteNotification($notificationId){
		DB::table('notifications')
			->where('id', '=', $notificationId)
			->delete();
	}

}
