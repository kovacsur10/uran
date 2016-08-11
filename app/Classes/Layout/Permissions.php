<?php

namespace App\Classes\Layout;

use DB;
use Illuminate\Database\QueryException;

class Permissions{
	
// PUBLIC FUNCTIONS
	
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
			->orderBy('id', 'asc')
			->get();
		return $permissions == null ? [] : $permissions;
	}
	
	public function getAllPermissions(){
		$permissions = DB::table('permissions')
			->orderBy('id', 'asc')
			->get();
		return $permissions == null ? [] : $permissions;
	}
	
	public function hasGuestsDefaultPermission($permissionId){
		return $this->getDefaultPermissions('guest', $permissionId);
	}
	
	public function hasCollegistsDefaultPermission($permissionId){
		return $this->getDefaultPermissions('collegist', $permissionId);
	}
	
	public function setDefaults($userType, $permissions){
		DB::beginTransaction();
		try{
			//first, delete all the permissions
			DB::table('default_permissions')
				->where('registration_type', 'LIKE', $userType)
				->delete();
			//add the new permissions
			foreach($permissions as $permission){
				DB::table('default_permissions')
					->insert([
						'registration_type' => $userType,
						'permission' => $permission
					]);
			}
			DB::commit();
			return 0;
		}catch(\Illuminate\Database\QueryException $e){
			DB::rollback();
			return 1;
		}
	}
	
// PRIVATE FUNCTIONS	
	
	private function getDefaultPermissions($userType, $permissionId){
		return DB::table('default_permissions')
			->where('registration_type', 'LIKE', $userType)
			->where('permission', '=', $permissionId)
			->orderBy('id', 'asc')
			->first();
	}
}
