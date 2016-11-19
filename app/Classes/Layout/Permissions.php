<?php

namespace App\Classes\Layout;

use App\Classes\Logger;
use App\Persistence\P_User;
use App\Persistence\P_General;
use App\Classes\Database;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\UserNotFoundException;

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
	public static function permitted($userId, $permissionName){
		$permissions = Permissions::getForUser($userId);
		$i = 0;
		while($i < count($permissions) && $permissions[$i]->name() !== $permissionName){
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
	 * @return array of Permission
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getForUser($userId){
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
	public static function getById($permissionId){
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
	public static function getByName($permissionName){
		if($permissionName === null){ //exceptional condition
			return null;
		}
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
	 * @return array of Permission
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getAllPermissions(){
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
	 * @return array of User
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getUsersWithPermission($permissionName){
		if($permissionName === null){
			return [];
		}
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
	public static function hasGuestsDefaultPermission($permissionId){
		return Permissions::hasDefaultPermission('guest', $permissionId);
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
	public static function hasCollegistsDefaultPermission($permissionId){
		return Permissions::hasDefaultPermission('collegist', $permissionId);
	}
	
	/** Function name: setDefaults
	 *
	 * This function sets the default
	 * permissions to the given user type.
	 * 
	 * @param text $userType - user type
	 * @param arrayOfId $permissions - identifiers of the permissions
	 * 
	 * @throws DatabaseException when the default permissions cannot be set!
	 * @throws ValueMismatchException when the user type not exist or anything is null!
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function setDefaults($userType, $permissions){
		if(($userType != "guest" && $userType != "collegist") || $permissions === null){
			throw new ValueMismatchException("The provided user type does not exist!");
		}
		try{
			Database::transaction(function() use($userType, $permissions){
				//first, delete all the permissions
				P_General::deleteDefaultPermissionsForRegistrationType($userType);
				//add the new permissions
				foreach($permissions as $permission){
					P_General::insertNewDefaultPermission($userType, $permission);
				}
			});
		}catch(\Exception $ex){
			throw new DatabaseException("Cannot set default permissions! (".$ex->getMessage().")");
		}
	}
	
	/** Function name: removeAll
	 *
	 * This function removes all of
	 * the user's currently possessed
	 * permissions.
	 * 
	 * @param int $userId - user's identifier
	 * 
	 * @throws DatabaseException when the persistence layer had an error.
	 * @throws UserNotFoundException when the given user id was not valid.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function removeAll($userId){
		if($userId === null){
			throw new UserNotFoundException();
		}
		try{
			P_User::removePermissionsForUser($userId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'user_permissions' was not successful! ".$ex->getMessage());
			throw new DatabaseException("Removing all permissions for user was not successful!");
		}
	}
	
	/** Function name: setPermissionForUser
	 *
	 * This function adds the reqested
	 * permission to the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @param int $permissionId - identifier of a permission
	 * 
	 * @throws DatabaseException when the persistence layer had an error.
	 * @throws UserNotFoundException when the given user id was not valid.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function setPermissionForUser($userId, $permissionId){
		if($userId === null){
			throw new UserNotFoundException();
		}
		if($permissionId === null){
			throw new DatabaseException("Permission identifier should not be null!");
		}
		try{
			P_User::addPermissionForUser($userId, $permissionId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'user_permissions' was not successful! ".$ex->getMessage());
			throw new DatabaseException("Setting a permission for user was not successful!");
		}
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
	private static function hasDefaultPermission($userType, $permissionId){
		if($userType === null || $permissionId === null){
			return false;
		}
		try{
			$permission = P_General::hasDefaultPermission($userType, $permissionId);
		}catch(\Exception $ex){
			$permission = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'default_permissions' was not successful! ".$ex->getMessage());
		}
		return ($permission !== null);
	}
}
