<?php

namespace App\Classes\Layout;

use DB;
use App\Classes\Layout\Permissions;
use App\Classes\Notifications;

class User{
	protected $user;
	protected $permissions;
	protected $notifications;
	protected $unseenNotificationCount;
	
	public function __construct($userId){
		$this->user = $this->getUserData($userId);
		$this->permissions = Permissions::get($userId);
		$this->notifications = Notifications::getNotifications($userId);
		$this->unseenNotificationCount = Notifications::getUnseenNotificationCount($userId);
	}
	
	public function user(){
		return $this->user;
	}
	
	public function users(){
		return DB::table('users')
			->select('id', 'username', 'name')
			->where('registered', '=', 1)
			->orderBy('name', 'asc')
			->get();
	}
	
	public function permissions(){
		return $this->permissions;
	}
	
	public function unseenNotificationCount(){
		return $this->unseenNotificationCount;
	}
	
	public function notificationCount(){
		return count($this->notifications);
	}
	
	public function latestNotifications(){
		if($this->notifications == null)
			return null;
		else if(count($this->notifications) <= 5)
			return $this->notifications;
		else
			return array_slice($this->notifications, 0, 5);
	}
	
	public function notifications($from, $count){
		if($this->notifications == null)
			return null;
		else if($from < 0 || count($this->notifications) < $from || $count < 0)
			return null;
		else if(count($this->notifications) < $from + $count)
			return array_slice($this->notifications, $from, count($this->notifications) - $from);
		else
			return array_slice($this->notifications, $from, $count);
	}
	
	public function permitted($what){
		$i = 0;
		while($i < count($this->permissions) && $this->permissions[$i]->permission_name != $what){
			$i++;
		}
		return $i < count($this->permissions);
	}
	
	public function getUserData($userId){
		return DB::table('users')->where('id', '=', $userId)
								 ->where('registered', '=', 1)
								 ->first();
	}
	
	public function getUserDataByUsername($username){
		return DB::table('users')->where('username', 'LIKE', $username)
								 ->where('registered', '=', 1)
								 ->first();
	}
	
}
