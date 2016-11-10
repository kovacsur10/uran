<?php

namespace App\Classes\Layout;

use App\Classes\Logger;
use App\Persistence\P_User;
use App\Persistence\P_General;

/** Class name: Permissions
 *
 * This class handles the permission
 * system of the page.
 *
 * Functionality:
 * 		- user permissions
 * 		- default permissions
 * 
 * Functions that can throw exceptions:
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Permissions{
	
// PRIVATE DATA
	
// PUBLIC FUNCTIONS
	
	/** Function name: permitted
	 *
	 * This function returns a boolean
	 * value. True is returned if the
	 * requested user has the requested
	 * permission.
	 * 
	 * @param int $userId - user's identifier
	 * @param text $permissionName - text identifier of the permission
	 * @return bool - permitted or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function permitted($userId, $permissionName){
		$permissions = $this->getForUser($userId);
		$i = 0;
		while($i < count($permissions) && $permissions[$i]->permission_name !== $permissionName){
			$i++;
		}
		return $i < count($permissions);
	}
	
	/** Function name: getForUser
	 *
	 * This function returns the available
	 * permissions of the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @return array of Permissions
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getForUser($userId){
		try{
			$permissions = P_User::getUserPermissions($userId);
		}catch(\Exception $ex){
			$permissions = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'permissions', joined to 'user_permissions' was not successful! ".$ex->getMessage());
		}
		return $permissions;
	}
	
	/** Function name: getById
	 *
	 * This function returns the permission
	 * data of the requested permission.
	 * 
	 * @param int $permissionId - identifier of permission
	 * @return Permission|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getById($permissionId){
		try{
			$permission = P_General::getPermissionById($permissionId);
		}catch(\Exception $ex){
			$permission = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'permissions' was not successful! ".$ex->getMessage());
		}
		return $permission;
	}
	
	/** Function name: getByName
	 *
	 * This function returns the permission
	 * data of the requested permission name.
	 *
	 * @param text $permissionId - name of a permission
	 * @return Permission|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getByName($permissionName){
		try{
			$permission = P_General::getPermissionByName($permissionName);
		}catch(\Exception $ex){
			$permission = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'permissions' was not successful! ".$ex->getMessage());
		}
		return $permission;
	}
	
	/** Function name: getAllPermissions
	 *
	 * This function returns all of
	 * the available permissions.
	 * 
	 * @return array of Permissions
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getAllPermissions(){
		try{
			$permissions = P_General::getPermissions();
		}catch(\Exception $ex){
			$permissions = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'permissions' was not successful! ".$ex->getMessage());
		}
		return $permissions;
	}
	
	/** Function name: getUsersWithPermission
	 *
	 * This function returns all of
	 * the users, who have the requested
	 * permission.
	 * 
	 * @param int $permissionId - identifier of permission
	 * @return array of Users
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getUsersWithPermission($permissionName){
		try{
			$users = P_User::getUsersWithPermission($permissionName);
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'permissions', joined to 'user_permissions' and 'users' was not successful! ".$ex->getMessage());
		}
		return $users;
	}
	
	/** Function name: hasGuestsDefaultPermission
	 *
	 * This function returns an boolean value
	 * which means that the permission is in the
	 * set of the guests' default permissions.
	 * 
	 * @param int $permissionId - identifier of permission
	 * @return bool - has the permission or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function hasGuestsDefaultPermission($permissionId){
		return ($this->hasDefaultPermission('guest', $permissionId) !== null);
	}
	
	/** Function name: hasCollegistsDefaultPermission
	 *
	 * This function returns an boolean value
	 * which means that the permission is in the
	 * set of the collegists' default permissions.
	 * 
	 * @param int $permissionId - identifier of permission
	 * @return bool - has the permission or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function hasCollegistsDefaultPermission($permissionId){
		return ($this->hasDefaultPermission('collegist', $permissionId) !== null);
	}
	
	/** Function name: setDefaults
	 *
	 * This function sets the default
	 * permissions to the given user type.
	 * 
	 * @param text $userType - user type
	 * @param arrayOfText $permissions - text identifiers of the permissions
	 * @return int - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function setDefaults($userType, $permissions){
		$errorCode = 0;
		P_General::beginTransaction();
		try{
			//first, delete all the permissions
			P_General::deleteDefaultPermissionsForRegistrationType();
			//add the new permissions
			foreach($permissions as $permission){
				P_General::insertNewDefaultPermission($userType, $permission);
			}
			P_General::commit();
		}catch(\Exception $ex){
			P_General::rollback();
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from or insert into table 'default_permissions' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
	/** Function name: removeAll
	 *
	 * This function removes all of
	 * the user's currently possessed
	 * permissions.
	 * 
	 * @param int $userId - user's identifier
	 * @return int - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function removeAll($userId){
		$error = 0;
		try{
			P_User::removePermissionsForUser($userId);
		}catch(\Exception $ex){
			$error = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'user_permissions' was not successful! ".$ex->getMessage());
		}
		return $error;
	}
	
	/** Function name: setPermissionForUser
	 *
	 * This function adds the reqested
	 * permission to the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @param int $permissionId - identifier of a permission
	 * @return int - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function setPermissionForUser($userId, $permissionId){
		$error = 0;
		try{
			P_User::addPermissionForUser($userId, $permissionId);
		}catch(\Exception $ex){
			$error = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'user_permissions' was not successful! ".$ex->getMessage());
		}
		return $error;
	}
	
// PRIVATE FUNCTIONS	
	
	/** Function name: hasDefaultPermission
	 *
	 * This function returns that boolean value
	 * that the requested user type has the 
	 * requested permission or not.
	 * 
	 * @param text $userType - user type, "collegist" or "guest"
	 * @param int $permissionId - indentifier of a permission
	 * @return bool
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function hasDefaultPermission($userType, $permissionId){
		try{
			$permission = P_General::hasDefaultPermission($userType, $permissionId);
		}catch(\Exception $ex){
			$permission = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'default_permissions' was not successful! ".$ex->getMessage());
		}
		return ($permission !== null);
	}
}
