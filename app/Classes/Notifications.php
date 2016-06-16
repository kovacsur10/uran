<?php

namespace App\Classes;

use App\Classes\User;
use Carbon\Carbon;
use DB;

class Notifications{

	public static function getNotifications($id){
		return DB::table('notifications')
			->join('users', 'users.id', '=', 'notifications.from')
			->select('users.name as name', 'users.username as username', 'notifications.id as id', 'notifications.subject as subject', 'notifications.message as message', 'notifications.time as time', 'notifications.seen as seen')
			->where('user_id', '=', $id)
			->orderBy('id', 'desc')
			->get();
	}
	
	public static function getUnseenNotificationCount($id){
		return DB::table('notifications')
			->where('user_id', '=', $id)
			->where('seen', '=', 'false')
			->count('id');
	}
	
}
