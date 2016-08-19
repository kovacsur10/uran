<?php

namespace App\Http\Controllers\Notification;

use App\Classes\LayoutData;
use App\Classes\Notifications;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller{	
	
	public function listNotifications($firstId){
		return view('notification.list', ["layout" => new LayoutData(),
										  "notificationId" => $firstId]);
	}
	
	public function showNotification($notificationId){
		$notification = Notifications::get($notificationId, Session::get('user')->id);
		if($notification === null){
			$layout = new LayoutData();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_notification_view_insufficient_permission'),
										 "url" => '/notification/list/0']);
		}else{
			Notifications::setSeen($notificationId);
			if($notification->route === null)
				return redirect('notification/list/0');
			else
				return redirect($notification->route);
		}
	}
}
