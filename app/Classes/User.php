<?php

namespace App\Classes;

use DB;

class User{
	protected $user;
	protected $permissions;
	protected $notifications;
	protected $unseenNotificationCount;
	
	public function __construct($id){
		$this->user = $this->getUserData($id);
		$this->permissions = $this->getPermissions($id);
		$this->notifications = $this->getNotifications($id);
		$this->unseenNotificationCount = $this->getUnseenNotificationCount($id);
	}
	
	public function user(){
		return $this->user;
	}
	
	public function users(){
		return DB::table('users')
			->select('id', 'username', 'name')
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
	
	protected function getUserData($id){
		return DB::table('users')->where('id', '=', $id)
								 ->first();
	}
	
	protected function getPermissions($id){
		return DB::table('permissions')->join('user_permissions', 'permissions.id', '=', 'user_permissions.permission_id')
									   ->select('permission_name')
									   ->where('user_permissions.user_id', '=', $id)
									   ->get();
	}
	
	protected function getNotifications($id){
		return DB::table('notifications')
			->join('users', 'users.id', '=', 'notifications.from')
			->select('users.name as name', 'users.username as username', 'notifications.id as id', 'notifications.subject as subject', 'notifications.message as message', 'notifications.time as time', 'notifications.seen as seen')
			->where('user_id', '=', $id)
			->orderBy('id', 'desc')
			->get();
	}
	
	protected function getUnseenNotificationCount($id){
		return DB::table('notifications')
			->where('user_id', '=', $id)
			->where('seen', '=', 'false')
			->count('id');
	}
}
