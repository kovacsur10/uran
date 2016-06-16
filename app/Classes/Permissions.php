<?php

namespace App\Classes;

use DB;

class Permissions{
	
	public function permitted($userId, $permissionName){
		$permissions = Permissions::get($userId);
		$i = 0;
		while($i < count($permissions) && $permissions[$i]->permission_name != $permissionName){
			$i++;
		}
		return $i < count($permissions);
	}
	
	public static function get($userId){
		$permissions = DB::table('permissions')->join('user_permissions', 'permissions.id', '=', 'user_permissions.permission_id')
			->select('permissions.id as id', 'permission_name', 'permissions.description as description')
			->where('user_permissions.user_id', '=', $userId)
			->get();
		return $permissions == null ? [] : $permissions;
	}
	
}
