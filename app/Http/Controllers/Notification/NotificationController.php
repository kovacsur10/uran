<?php

namespace App\Http\Controllers\Notification;

use App\Classes\Auth;
use App\Classes\LayoutData;
use App\Classes\Notifications;
use App\Http\Controllers\Controller;

/** Class name: NotificationController
 *
 * This controller is for handling the notifications.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class NotificationController extends Controller{	
	
	/** Function name: listNotifications
	 *
	 * This function shows the list of the notificaitons.
	 *
	 * @param int $firstId - the first identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function listNotifications($firstId){
		return view('notification.list', ["layout" => new LayoutData(),
										  "notificationId" => $firstId]);
	}
	
	/** Function name: showNotification
	 *
	 * This function shows a notification.
	 *
	 * @param int $notificationId - the notification identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showNotification($notificationId){
		$notification = Notifications::get($notificationId, Auth::user()->id());
		if($notification === null){
			$layout = new LayoutData();
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_notification_view_insufficient_permission'),
										 "url" => '/notification/list/0']);
		}else{
			try{
				Notifications::setRead($notificationId);
			}catch(\Exception $ex){
			}
			if($notification->route() === null)
				return redirect('notification/list/0');
			else
				return redirect($notification->route());
		}
	}
	
	/** Function name: showNotification
	 *
	 * This function shows a notification.
	 *
	 * @param int $notificationId - the notification identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function readAll(){
		$layout = new LayoutData();
		if($layout->user()->permitted('notifications_readall')){
			try{
				Notifications::setReadAll($layout->user()->user()->id());
			}catch(\Exception $ex){
			}
			return redirect('notification/list/0');
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
}
