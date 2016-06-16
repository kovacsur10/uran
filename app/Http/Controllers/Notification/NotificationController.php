<?php

namespace App\Http\Controllers\Notification;

use App\Classes\LayoutData;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class NotificationController extends Controller{	
	
	public function listNotifications($firstId){
		return view('notification.list', ["layout" => new LayoutData(),
										  "notificationId" => $firstId]);
	}
	
	public function showNotification($notificationId){
		$exist = DB::table('notifications')
					->where('id', '=', $notificationId)
					->where('user_id', '=', Session::get('user')->id)
					->first();
		if($exist == null){
			$layout = new LayoutData();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_notification_view_insufficient_permission'),
										 "url" => '/notification/list/0']);
		}else{
			DB::table('notifications')
				->where('id', '=', $notificationId)
				->update(['seen' => 'true']);
			if($exist->route == null)
				return redirect('notification/list/0');
			else
				return redirect($exist->route);
		}
	}
}
