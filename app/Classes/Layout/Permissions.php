<?php

namespace App\Classes\Layout;

use DB;
use App\Classes\Logger;

/* Class name: Permissions
 *
 * This class handles the permission
 * system of the page.
 *
 * Functionality:
 * 		- user permissions
 * 		- default permissions
 * 
 * Functions that can throw exceptions:
 */
class Permissions{
	
// PRIVATE DATA
	
// PUBLIC FUNCTIONS
	
	/* Function name: permitted
	 * Input: 	$userId (integer) - user's identifier
	 * 			$permissionName (text) - text identifier of the permission
	 * Output: bool (permitted or not)
	 *
	 * This function returns a boolean
	 * value. True is returned if the
	 * requested user has the requested
	 * permission.
	 */
	public function permitted($userId, $permissionName){
		$permissions = $this->getForUser($userId);
		$i = 0;
		while($i < count($permissions) && $permissions[$i]->permission_name !== $permissionName){
			$i++;
		}
		return $i < count($permissions);
	}
	
	/* Function name: getForUser
	 * Input: $userId (integer) - user's identifier
	 * Output: array of permissions
	 *
	 * This function returns the available
	 * permissions of the requested user.
	 */
	public function getForUser($userId){
		try{
			$permissions = DB::table('permissions')
				->join('user_permissions', 'permissions.id', '=', 'user_permissions.permission_id')
				->select('permissions.id as id', 'permission_name', 'permissions.description as description')
				->where('user_permissions.user_id', '=', $userId)
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$permissions = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'permissions', joined to 'user_permissions' was not successful! ".$ex->getMessage());
		}
		return $permissions;
	}
	
	/* Function name: getById
	 * Input: $permissionId (integer) - identifier of permission
	 * Output: Permission
	 *
	 * This function returns the permission
	 * data of the requested permission.
	 */
	public function getById($permissionId){
		try{
			$permission = DB::table('permissions')
				->where('permissions.id', '=', $permissionId)
				->first();
		}catch(\Exception $ex){
			$permission = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'permissions' was not successful! ".$ex->getMessage());
		}
		return $permission;
	}
	
	/* Function name: getAllPermissions
	 * Input: -
	 * Output: array of permissions
	 *
	 * This function returns all of
	 * the available permissions.
	 */
	public function getAllPermissions(){
		try{
			$permissions = DB::table('permissions')
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$permissions = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'permissions' was not successful! ".$ex->getMessage());
		}
		return $permissions;
	}
	
	/* Function name: getUsersWithPermission
	 * Input: $permissionId (integer) - identifier of permission
	 * Output: array of users
	 *
	 * This function returns all of
	 * the users, who have the requested
	 * permission.
	 */
	public function getUsersWithPermission($permissionId){
		try{
			$users = DB::table('permissions')
				->join('user_permissions', 'user_permissions.permission_id', '=', 'permissions.id')
				->join('users', 'users.id', '=', 'user_permissions.user_id')
				->where('permissions.id', '=', $permissionId)
				->select('users.id', 'users.name', 'users.username')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'permissions', joined to 'user_permissions' and 'users' was not successful! ".$ex->getMessage());
		}
		return $users;
	}
	
	/* Function name: hasGuestsDefaultPermission
	 * Input: $permissionId (integer) - identifier of permission
	 * Output: bool (has the permission or not)
	 *
	 * This function returns an boolean value
	 * which means that the permission is in the
	 * set of the guests' default permissions.
	 */
	public function hasGuestsDefaultPermission($permissionId){
		return ($this->getDefaultPermissions('guest', $permissionId) !== null);
	}
	
	/* Function name: hasCollegistsDefaultPermission
	 * Input: $permissionId (integer) - identifier of permission
	 * Output: bool (has the permission or not)
	 *
	 * This function returns an boolean value
	 * which means that the permission is in the
	 * set of the collegists' default permissions.
	 */
	public function hasCollegistsDefaultPermission($permissionId){
		return ($this->getDefaultPermissions('collegist', $permissionId) !== null);
	}
	
	/* Function name: setDefaults
	 * Input: 	$userType (text) - user type
	 * 			$permissions (array of text) - text identifiers of the permissions
	 * Output: integer (error code)
	 *
	 * This function sets the default
	 * permissions to the given user type.
	 */
	public function setDefaults($userType, $permissions){
		$errorCode = 0;
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
		}catch(\Exception $ex){
			DB::rollback();
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from or insert into table 'default_permissions' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
	/* Function name: removeAll
	 * Input: $userId (integer) - user's identifier
	 * Output: integer (error code)
	 *
	 * This function removes all of
	 * the user's currently possessed
	 * permissions.
	 */
	public function removeAll($userId){
		$error = 0;
		try{
			DB::table('user_permissions')
				->where('user_id', '=', $userId)
				->delete();
		}catch(\Exception $ex){
			$error = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'user_permissions' was not successful! ".$ex->getMessage());
		}
		return $error;
	}
	
	/* Function name: setPermissionForUser
	 * Input: 	$userId (integer) - user's identifier
	 * 			$permissionId (integer) - identifier of a permission
	 * Output: integer (error code)
	 *
	 * This function adds the reqested
	 * permission to the requested user.
	 */
	public function setPermissionForUser($userId, $permissionId){
		$error = 0;
		try{
			DB::table('user_permissions')
				->insert([
					'user_id' => $userId,
					'permission_id' => $permissionId
				]);
		}catch(\Exception $ex){
			$error = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'user_permissions' was not successful! ".$ex->getMessage());
		}
		return $error;
	}
	
// PRIVATE FUNCTIONS	
	
	/* Function name: getDefaultPermissions
	 * Input: 	$userType (text) - user type
	 * 			$permissionId (integer) - indentifier of a permission
	 * Output: integer (error code)
	 *
	 * This function returns the permission
	 * based on the requested user type
	 * and the permission.
	 */
	private function getDefaultPermissions($userType, $permissionId){
		try{
			$permission = $DB::table('default_permissions')
				->where('registration_type', 'LIKE', $userType)
				->where('permission', '=', $permissionId)
				->orderBy('id', 'asc')
				->first();
		}catch(\Exception $ex){
			$permission = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'default_permissions' was not successful! ".$ex->getMessage());
		}
		return $permission;
	}
}
