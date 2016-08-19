<?php

namespace App\Classes;

use Carbon\Carbon;
use DB;

class Notifications{

	public static function getNotifications($userId){
		return DB::table('notifications')
			->join('users', 'users.id', '=', 'notifications.from')
			->select('users.name as name', 'users.username as username', 'notifications.id as id', 'notifications.subject as subject', 'notifications.message as message', 'notifications.time as time', 'notifications.seen as seen')
			->where('user_id', '=', $userId)
			->orderBy('id', 'desc')
			->get();
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
