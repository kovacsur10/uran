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
		$tmpPermissions = new Permissions();
		$this->permissions = $tmpPermissions->getForUser($userId);
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
	
	public function usersAllData($from = 0, $count = 50){
		$ret = DB::table('users')
			->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->where('registered', '=', 1)
			->select('*', 'users.id as id')
			->orderBy('name', 'asc')
			->skip($from)
			->take($count)
			->get();
		return $ret === null ? [] : $ret;
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
		if($userId === 0){
			return DB::table('users')
				->where('id', '=', 0)
				->first();
		}else{	
			return DB::table('users')
				->where('id', '=', $userId)
				->where('registered', '=', 1)
				->first();
		}
	}
	
	// Function name: getUserData_Administration
	// Input: user id (integer)
	// Output: the requested user's data
	//
	// This function returns the requested user's full data.
	// Not only the user table, but it joins a lot more table
	// and gives all the informations stored in the database 
	// about the target. (Excluded the modules.)
	public function getUserData_Administration($userId){
		if($userId === 0){
			return null;
		}else{	
			return DB::table('users')
				->where('users.id', '=', $userId)
				->where('users.registered', '=', 1)
				->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
				->join('workshops', 'workshops.id', '=', 'users.workshop')
				->join('faculties', 'faculties.id', '=', 'users.faculty')
				->select('users.id as id', 'users.username as username', 'users.email as email', 'users.registration_date as registration_date', 'users.name as name', 'users.country as country', 'users.shire as shire', 'users.city as city', 'users.postalcode as postalcode', 'users.address as address', 'users.phone as phone', 'users.reason as reason', 'users.neptun as neptun', 'users.city_of_birth as city_of_birth', 'users.date_of_birth as date_of_birth', 'users.name_of_mother as name_of_mother', 'users.high_school as high_school', 'users.year_of_leaving_exam as year_of_leaving_exam', 'user_status_codes.status_name as status', 'user_status_codes.id as status_id', 'workshops.name as workshop', 'workshops.id as workshop_id', 'faculties.name as faculty', 'faculties.id as faculty_id', 'users.from_year as admission_year')
				->first();
		}
	}
	
	public function getUserDataByUsername($username){
		return DB::table('users')
			->where('username', 'LIKE', $username)
			->where('registered', '=', 1)
			->first();
	}
	
	// Function name: saveUserLanguage
	// Input: language identifier (string)
	// Output: -
	//
	// DB EXCEPTION!
	// This function updates the user default language.
	public function saveUserLanguage($lang){
		DB::table('users')
			->where('id', '=', $this->user->id)
			->update([
				'language' => $lang
			]);
	}
	
}
