<?php

namespace App\Classes;

use App\Classes\User;
use Carbon\Carbon;
use DB;

class Notify{

	public static function notify(User $from, $toId, $subject, $message, $route){
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
		}
	}
	
}
