<?php

namespace App\Classes;

use App\Classes\User;
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
			if($count != null && $count > max_count){
				$notifications = DB::table('notifications')
									->where('user_id', '=', $toId)
									->orderBy('id', 'asc')
									->take($count - max_count)
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
	
}
