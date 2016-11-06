<?php

namespace App\Persistence;

use DB;

/* Function name: beginTransaction
 * Input: -
 * Output: -
 *
 * This function starts a new database transaction.
 */
function beginTransaction(){
	DB::beginTransaction();
}

/* Function name: rollback
 * Input: -
 * Output: -
 *
 * This function rollback a database transaction.
 */
function rollback(){
	DB::rollback();
}

/* Function name: commit
 * Input: -
 * Output: -
 *
 * This function commits a database transaction.
 */
function commit(){
	DB::commit();
}

/* Function name: writeIntoLog
 * Input: 	$description (text) - short description
 * 			$oldValue (text) - if changed, the old value
 * 			$newValue (text) - if changed, the new value
 * 			$route (text) - route to the page
 * 			$userId (int) - user's identifier
 * 			$datetime (date) - timestamp
 * 			$severity (int) - severity of the log
 * Output: -
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
 */
function writeIntoLog($description, $oldValue, $newValue, $route, $userId, $datetime, $severity){
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

/* Function name: insertNewNotification
 * Input: 	$toId (int) - sender user's identifier
 * 			$fromId (int) - receiver user's identifier
 * 			$subject (text) - subject of the notification
 * 			$message (text) - content of the notification
 * 			$datetime (datetime) - 
 * 			$admin (bool) - 
 * 			$route (text) - route to the source page
 * Output: -
 *
 * This function insert a new notification
 * to the database based on the given data.
 */
function insertNewNotification($toId, $fromId, $subject, $message, $datetime, $admin, $route = null){
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

/* Function name: insertNewNotification
 * Input: $notificationId (int) - notification identifier
 * Output: -
 *
 * This function sets the 'seen' property of
 * the notification as seen (true).
 */
function setNotificationAsSeen($notificationId){
	DB::table('notifications')
		->where('id', '=', $notificationId)
		->update([
			'seen' => 'true
		']);
}

/* Function name: insertNewNotification
 * Input: 	$notificationId (int) - notification identifier
 * 			$userId (int) - user's identifier
 * Output: notification|NULL
 *
 * This function returns the requested notification.
 * If the notification is not sent to the requested
 * user, the returned value is NULL.
 */
function getNotification($notificationId, $userId){
	return DB::table('notifications')
		->where('id', '=', $notificationId)
		->where('user_id', '=', $userId)
		->first();
}

/* Function name: getNotificationCountForUser
 * Input: $userId (int) - user's identifier
 * Output: int (count)
 *
 * This function returns the count of the
 * notifications for the requested user.
 */
function getNotificationCountForUser($userId){
	DB::table('notifications')
	->where('user_id', '=', $userId)
	->count('id');
}

/* Function name: getUnseenNotificationCountForUser
 * Input: $userId (int) - user's identifier
 * Output: int (count)
 *
 * This function returns the count of the unseen
 * notifications for the requested user.
 */
function getUnseenNotificationCountForUser($userId){
	DB::table('notifications')
		->where('user_id', '=', $userId)
		->where('seen', '=', 'false')
		->count('id');
}

/* Function name: getNotificationsForUser
 * Input: $userId (int) - receiver user's identifier
 * Output: array of notifications 
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
 * This function returns the notifications for
 * the requested user.
 */
function getNotificationsForUser($userId){
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
function getOldestNonAdminNotificationsForUser($userId, $count){
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
function deleteNotification($notificationId){
	DB::table('notifications')
		->where('id', '=', $notificationId)
		->delete();
}
