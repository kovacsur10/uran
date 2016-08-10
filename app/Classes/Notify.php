<?php

namespace App\Classes;

use App\Classes\Layout\User;
use Carbon\Carbon;
use DB;

class Notify{

	public static function notify(User $from, $toId, $subject, $message, $route){
		$max_count = 100;
		
		if($from != null && $toId != null && $subject != null && $message != null){
			if($route == null){
				DB::table('notifications')
					->insert(['user_id' => $toId,
							  'subject' => $subject,
							  'message' => $message,
							  'from' => $from->user()->id,
							  'time' => Carbon::now()->toDateTimeString()]);
			}else{
				DB::table('notifications')
					->insert(['user_id' => $toId,
							  'subject' => $subject,
							  'message' => $message,
							  'from' => $from->user()->id,
							  'route' => $route,
							  'time' => Carbon::now()->toDateTimeString()]);
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
				if($notifications != null)
					foreach($notifications as $notify){
						DB::table('notifications')
							->where('id', '=', $notify->id)
							->delete();
					}
			}
		}
	}
	
	public static function notifyAdmin(User $from, $adminPermission, $subject, $message, $route){
		if($from != null && $adminPermission != null && $subject != null && $message != null){
			$admins = DB::table('users')
						->join('user_permissions', 'user_permissions.user_id', '=', 'users.id')
						->join('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
						->where('permissions.permission_name', 'LIKE', $adminPermission)
						->select('users.id as id')
						->get();
			if($admins != null){
				foreach($admins as $admin){
					if($route == null){
						DB::table('notifications')
							->insert(['user_id' => $admin->id,
									  'subject' => $subject,
									  'message' => $message,
									  'from' => $from->user()->id,
									  'time' => Carbon::now()->toDateTimeString(),
									  'admin' => 'true']);
					}else{
						DB::table('notifications')
							->insert(['user_id' => $admin->id,
									  'subject' => $subject,
									  'message' => $message,
									  'from' => $from->user()->id,
									  'route' => $route,
									  'time' => Carbon::now()->toDateTimeString(),
									  'admin' => 'true']);
					}
				}
			}
		}
	}
	
	public static function notifyAdminFromServer($adminPermission, $subject, $message, $route){
		if($adminPermission != null && $subject != null && $message != null){
			$admins = DB::table('users')
						->join('user_permissions', 'user_permissions.user_id', '=', 'users.id')
						->join('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
						->where('permissions.permission_name', 'LIKE', $adminPermission)
						->select('users.id as id')
						->get();
			if($admins != null){
				foreach($admins as $admin){
					if($route == null){
						DB::table('notifications')
							->insert(['user_id' => $admin->id,
									  'subject' => $subject,
									  'message' => $message,
									  'from' => 0,
									  'time' => Carbon::now()->toDateTimeString(),
									  'admin' => 'true']);
					}else{
						DB::table('notifications')
							->insert(['user_id' => $admin->id,
									  'subject' => $subject,
									  'message' => $message,
									  'from' => 0,
									  'route' => $route,
									  'time' => Carbon::now()->toDateTimeString(),
									  'admin' => 'true']);
					}
				}
			}
		}
	}
	
}
