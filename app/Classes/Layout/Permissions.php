<?php

namespace App\Classes\Layout;

use App\Classes\Logger;
use App\Persistence\P_User;
use App\Persistence\P_General;
use App\Classes\Database;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\UserNotFoundException;
use App\Classes\Data\PermissionGroup;

/** Class name: Permissions
 *
 * This class handles the permission
 * system of the page.
 *
 * Functionality:
 * 		- user permissions
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
	 * permission or has the permission from a group.
	 * 
	 * @param int $userId - user's identifier
	 * @param text $permissionName - text identifier of the permission
	 * @return bool - permitted or not
	 * 
	 * @throws ValueMismatchException - if a parameter is null.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function permitted($userId, $permissionName){
		if($userId === null || $permissionName === null){
			throw new ValueMismatchException("Parameters cannot be null!");
		}
		$permissions = Permissions::getForUser($userId);
		
		foreach($permissions as $permission){
			if($permission->name() === $permissionName){
				return true;
			}
		}
		return false;
	}
	
	/** Function name: permittedExplicitly
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
	 * @throws ValueMismatchException - if a parameter is null.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function permittedExplicitly($userId, $permissionName){
		if($userId === null || $permissionName === null){
			throw new ValueMismatchException("Parameters cannot be null!");
		}
		$permissions = Permissions::getForUserExplicitPermissions($userId);
	
		foreach($permissions as $permission){
			if($permission->name() === $permissionName){
				return true;
			}
		}
		return false;
	}
	
	/** Function name: permittedFromGroups
	 *
	 * This function returns a boolean
	 * value. True is returned if the
	 * requested user has the requested
	 * permission from groups.
	 *
	 * @param int $userId - user's identifier
	 * @param text $permissionName - text identifier of the permission
	 * @return bool - permitted or not
	 *
	 * @throws ValueMismatchException - if a parameter is null.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function permittedFromGroups($userId, $permissionName){
		if($userId === null || $permissionName === null){
			throw new ValueMismatchException("Parameters cannot be null!");
		}
		$permissions = Permissions::getForUserFromGroups($userId);
	
		foreach($permissions as $permission){
			if($permission->name() === $permissionName){
				return true;
			}
		}
		return false;
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
		if($userId === null){
			return [];
		}
		try{
			$permissionsFromGroups = Permissions::getForUserFromGroups($userId);
			$permissions = Permissions::getForUserExplicitPermissions($userId);
			$permissions = array_unique(array_merge($permissions, $permissionsFromGroups));
		}catch(\Exception $ex){
			$permissions = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $permissions;
	}
	
	/** Function name: getForUserExplicitPermissions
	 *
	 * This function returns the available
	 * permissions of the requested user,
	 * that were explicitly given to the user.
	 * 
	 * @param int $userId - user's identifier
	 * @return array of Permission
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getForUserExplicitPermissions($userId){
		try{
			$permissions = P_User::getUserPermissions($userId);
		}catch(\Exception $ex){
			$permissions = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $permissions;
	}
	
	/** Function name: getUsersWithPermission
	 *
	 * This function returns all of
	 * the users, who have the requested
	 * permission.
	 * 
	 * @param string $permissionName - name identifier of permission
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $users;
	}
	
	/** Function name: getUsersWithGroup
	 *
	 * This function returns all of
	 * the users, who are member of the
	 * requested group.
	 *
	 * @param int $groupId - identifier of group
	 * @return array of User
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getUsersWithGroup($groupId){
		if($groupId === null){
			return [];
		}
		try{
			$users = P_User::getUsersWithGroup($groupId);
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $users;
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Setting a permission for user was not successful!");
		}
	}
	
	/** Function name: getPermissionGroups
	 * 
	 * This function returns the available
	 * permission groups.
	 * 
	 * @return array of PermissionGroup
	 * 
	 * @throws DatabaseException if a database exception has occurred.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getPermissionGroups(){
		try{
			$groups = P_General::getPermissionGroups();
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not get the permission groups!");
		}
		return $groups;
	}
	
	/** Function name: getPermissionGroup
	 *
	 * This function returns the requested permission group.
	 *
	 * @param int $id - identifier of a permissions group
	 * @return PermissionGroup
	 *
	 * @throws ValueMismatchException if the given identifier is null.
	 * @throws DatabaseException if a database exception has occurred.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getPermissionGroup($id){
		if($id === null){
			throw new ValueMismatchException("Identifier cannot be null!");
		}
		try{
			$group = P_General::getPermissionGroup($id);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not get the permission group!");
		}
		if($group === null){
			throw new DatabaseException("Element could not be found!");
		}
		return $group;
	}
	
	/** Function name: setGroupPersmissions
	 *
	 * This function returns the requested permission group.
	 *
	 * @param int $groupId - identifier of a permission group
	 * @param array $permissionsToHave - permission id array
	 *
	 * @throws ValueMismatchException if the given identifier is null.
	 * @throws DatabaseException if a database exception has occurred.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function setGroupPermissions($groupId, $permissionsToHave){
		if($groupId === null || $permissionsToHave === null || !is_array($permissionsToHave)){
			throw new ValueMismatchException("Parameter values are not accepted with null value!");
		}
		$group = Permissions::getPermissionGroup($groupId);
		$newPermissions = $permissionsToHave;
		$deletablePermissions = [];
		foreach($group->permissions() as $perm){
			$key = array_search($perm->id(), $newPermissions);
			if($key !== false){
				unset($newPermissions[$key]);
			}else{
				$deletablePermissions[] = $perm->id();
			}
		}
		try{
			Database::transaction(function() use($groupId, $deletablePermissions, $newPermissions){
				foreach($deletablePermissions as $permId){
					P_General::deleteGroupPermission($groupId, $permId);
				}
				foreach($newPermissions as $permId){
					P_General::addGroupPermission($groupId, $permId);
				}
			});
		}catch(\Exception $ex){
			throw new DatabaseException("The permissions handling failed!");
		}
	}
	
	/** Function name: getGroupsForUser
	 *
	 * This function returns the user's permission groups.
	 * 
	 * @param int $userId - user's identifier
	 * @return array of PermissionGroup
	 * 
	 * @throws ValueMismatchException - if a parameter is null.
	 * @throws DatabaseException - if a database error has occurred.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getGroupsForUser($userId){
		if($userId === null){
			throw new ValueMismatchException("Identifier cannot be null!");
		}
		try{
			$groups = P_User::getUserPermissionGroups($userId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not get the permission groups!");
		}
		return $groups;
	}
	
	/** Function name: getForUserFromGroups
	 *
	 * This function returns the user's permissions from the
	 * group the user is in.
	 * 
	 * @param int $userId - user's identifier
	 * @return array of Permission
	 * 
	 * @throws ValueMismatchException - if a parameter is null.
	 * @throws DatabaseException - if a database error has occurred.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getForUserFromGroups($userId){
		$permissions = [];
		if($userId === null){
			throw new ValueMismatchException("Identifier cannot be null!");
		}
		try{
			$groups = Permissions::getGroupsForUser($userId);
			foreach($groups as $group){
				$permissions = array_merge($permissions, $group->permissions());
			}
			$permissions = array_unique($permissions);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not create the appended permission list!");
		}
		return $permissions;
	}
	
	/** Function name: memberOfPermissionGroups
	 *
	 * This function returns if the a user is
	 * the member of a group or not.
	 * 
	 * @param int $userId - user's identifier
	 * @param int $groupId - identifier of the group
	 * @return bool - member of the group or not
	 *
	 * @throws ValueMismatchException if a parameter value is null.
	 * @throws DatabaseException if a database exception has occurred.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function memberOfPermissionGroups($userId, $groupId){
		if($userId === null || $groupId === null){
			throw new ValueMismatchException("Parameter values cannot be null!");
		}
		try{
			$groups = Permissions::getGroupsForUser($userId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not get the permission groups!");
		}
		foreach($groups as $group){
			if($group->id() === $groupId){
				return true;
			}
		}
		return false;
	}
	
	/** Function name: memberOfPermissionGroups
	 *
	 * This function returns if the a user is
	 * the member of a group or not.
	 *
	 * @param int $userId - user's identifier
	 * @param array $groupIds - identifiers of the groups
	 *
	 * @throws ValueMismatchException if a parameter value is null.
	 * @throws DatabaseException if a database exception has occurred.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function saveUserPermissionGroups($userId, $groupIds){
		if($userId === null){
			throw new ValueMismatchException("Parameter values cannot be null!");
		}
		if($groupIds === null || !is_array($groupIds)){
			$groupIds = [];
		}
		try{
			P_User::setUserPermissionGroups($userId, $groupIds);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not set the permission groups for the user!");
		}
	}
	
// PRIVATE FUNCTIONS	
}
