<?php

namespace App\Persistence;

use DB;
use App\Classes\Data\Faculty;
use App\Classes\Data\Workshop;
use App\Classes\Data\Country;
use App\Classes\Data\Module;
use App\Classes\Data\Permission;
use App\Classes\Data\Notification;
use App\Classes\Data\PermissionGroup;

/** Class name: P_General
 *
 * This class is the database persistence layer class
 * for the general information tables.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class P_General{

//DATABASE FUNCTIONS
	
	/** Function name: beginTransaction
	 *
	 * This function starts a new database transaction.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function beginTransaction(){
		DB::beginTransaction();
	}
	
	/** Function name: rollback
	 *
	 * This function rollback a database transaction.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function rollback(){
		DB::rollback();
	}
	
	/** Function name: commit
	 *
	 * This function commits a database transaction.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function commit(){
		DB::commit();
	}

//LOG FUNCTIONS
	
	/** Function name: writeIntoLog
	 *
	 * This function writes log into the database.
	 * Severity of the log can be:
	 * 		1 - normal log
	 * 		2 - warning
	 * 		3 - error
	 *
	 * The old value and new value can be empty strings.
	 * If a value is changed according to these fields
	 * the old data can be recovered.
	 *
	 * A valid route should be added for debugging reasons.
	 * 
	 * @param text $description - short description
	 * @param text $oldValue - if changed, the old value
	 * @param text $newValue - if changed, the new value
	 * @param text $route - route to the page
	 * @param int $userId - user's identifier
	 * @param datetime $datetime - timestamp
	 * @param int $severity - severity of the log
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function writeIntoLog($description, $oldValue, $newValue, $route, $userId, $datetime, $severity){
		DB::table('logs')
			->insert([
					'description' => $description,
					'old_value' => print_r($oldValue, true),
					'new_value' => print_r($newValue, true),
					'path' => $route,
					'user_id' => $userId,
					'datetime' => $datetime,
					'type' => $severity,
			]);
	}
	
//NOTIFICATION FUNCTIONS
	
	/** Function name: insertNewNotification
	 *
	 * This function insert a new notification
	 * to the database based on the given data.
	 * 
	 * @param int $toId - sender user's identifier
	 * @param int $fromId - receiver user's identifier
	 * @param text $subject - subject of the notification
	 * @param text $message - content of the notification
	 * @param datetime $datetime - notification creation time 
	 * @param bool $admin - admin mode notification or not
	 * @param text $route - route to the source page
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function insertNewNotification($toId, $fromId, $subject, $message, $datetime, $admin, $route = null){
		DB::table('notifications')
			->insert([
					'user_id' => $toId,
					'subject' => $subject,
					'message' => $message,
					'from' => $fromId,
					'route' => $route,
					'time' => $datetime,
					'admin' => $admin
			]);
	}
	
	/** Function name: setNotificationAsSeen
	 *
	 * This function sets the 'seen' property of
	 * the notification as seen (true).
	 * 
	 * @param int $notificationId - notification identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function setNotificationAsSeen($notificationId){
		DB::table('notifications')
			->where('id', '=', $notificationId)
			->update([
				'seen' => 'true
			']);
	}
	
	/** Function name: getNotification
	 *
	 * This function returns the requested notification.
	 * If the notification is not sent to the requested
	 * user, the returned value is null.
	 * 
	 * @param int $notificationId - notification identifier
	 * @param int $userId - user's identifier
	 * @return Notification|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getNotification($notificationId, $userId){
		$notification = DB::table('notifications')
			->join('users', 'users.id', '=', 'notifications.from')
			->select('notifications.id as id', 'users.name as name', 'users.username as username', 'notifications.subject as subject', 'notifications.message as message', 'notifications.time as time', 'notifications.seen as seen', 'notifications.admin as admin', 'notifications.route as route')
			->where('notifications.id', '=', $notificationId)
			->where('notifications.user_id', '=', $userId)
			->first();
		return $notification === null ? null : new Notification($notification->id, $notification->name, $notification->username, $notification->subject, $notification->message, $notification->time, $notification->seen, $notification->admin, $notification->route);
	}
	
	/** Function name: getNotificationCountForUser
	 *
	 * This function returns the count of the
	 * notifications for the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @return int - count
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getNotificationCountForUser($userId){
		return DB::table('notifications')
			->where('user_id', '=', $userId)
			->count('id');
	}
	
	/** Function name: getUnseenNotificationCountForUser
	 *
	 * This function returns the count of the unseen
	 * notifications for the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @return int - count
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUnseenNotificationCountForUser($userId){
		return DB::table('notifications')
			->where('user_id', '=', $userId)
			->where('seen', '=', 'false')
			->count('id');
	}
	
	/** Function name: getNotificationsForUser
	 *
	 * This function returns the notifications for
	 * the requested user.
	 * 
	 * @param int $userId - receiver user's identifier
	 * @return array of Notification
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getNotificationsForUser($userId){
		$rawNotifications = DB::table('notifications')
			->join('users', 'users.id', '=', 'notifications.from')
			->select('notifications.id as id', 'users.name as name', 'users.username as username', 'notifications.subject as subject', 'notifications.message as message', 'notifications.time as time', 'notifications.seen as seen', 'notifications.admin as admin', 'notifications.route as route')
			->where('notifications.user_id', '=', $userId)
			->orderBy('notifications.id', 'desc')
			->get()
			->toArray();
		$notifications = [];
		foreach($rawNotifications as $notification){
			array_push($notifications, new Notification($notification->id, $notification->name, $notification->username, $notification->subject, $notification->message, $notification->time, $notification->seen, $notification->admin, $notification->route));
		}
		return $notifications;
	}
	
	/** Function name: getOldestNonAdminNotificationsForUser
	 *
	 * This function returns the last $count
	 * notifications for the requested user.
	 * Admin flagged notifications are not
	 * returned.
	 *
	 * @param int $userId - user's identifier
	 * @param int $count - count of notifications
	 * @return array of notifications
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getOldestNonAdminNotificationsForUser($userId, $count){
		$rawNotifications = DB::table('notifications')
			->join('users', 'users.id', '=', 'notifications.from')
			->select('notifications.id as id', 'users.name as name', 'users.username as username', 'notifications.subject as subject', 'notifications.message as message', 'notifications.time as time', 'notifications.seen as seen', 'notifications.admin as admin', 'notifications.route as route')
			->where('notifications.user_id', '=', $userId)
			->where('notifications.admin', '=', 'false')
			->orderBy('notifications.id', 'asc')
			->take($count)
			->get()
			->toArray();
		$notifications = [];
		foreach($rawNotifications as $notification){
			array_push($notifications, new Notification($notification->id, $notification->name, $notification->username, $notification->subject, $notification->message, $notification->time, $notification->seen, $notification->admin, $notification->route));
		}
		return $notifications;
	}
	
	/** Function name: deleteNotification
	 *
	 * This function removes the requested notification
	 * from the database.
	 * 
	 * @param int $notificationId - notification identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function deleteNotification($notificationId){
		DB::table('notifications')
			->where('id', '=', $notificationId)
			->delete();
	}
	
//BASE FUNCTIONS
	
	/** Function name: getCountries
	 * 
	 * This function returns the countries
	 * from the database.
	 * 
	 * @return array of Country
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getCountries(){
		$getCountries = DB::table('country')
			->get();
		$countries = [];
		foreach($getCountries as $country){
			array_push($countries, new Country($country->id, $country->name));
		}
		return $countries;
	}
	
	/** Function name: getFaculties
	 *
	 * This function returns the faculties
	 * from the database.
	 *
	 * @return array of Faculty
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getFaculties(){
		$getFaculties = DB::table('faculties')
			->orderBy('id', 'asc')
			->get();
		$faculties = [];
		foreach($getFaculties as $faculty){
			array_push($faculties, new Faculty($faculty->id, $faculty->name));
		}
		return $faculties;
	}
	
	/** Function name: getWorkshops
	 *
	 * This function returns the workshops of the dormitory
	 * from the database.
	 *
	 * @return array of Workshop
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getWorkshops(){
		$getWorkhops = DB::table('workshops')
			->orderBy('id', 'asc')
			->get();
		$workshops = [];
		foreach($getWorkhops as $workshop){
			array_push($workshops, new Workshop($workshop->id, $workshop->name));
		}
		return $workshops;
	}
	
	/** Function name: getAdmissionYears
	 *
	 * This function returns the admission years
	 * from the database.
	 *
	 * @return array of years
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getAdmissionYears(){
		return DB::table('admission_years')
			->orderBy('year', 'asc')
			->pluck('year');
	}
	
//MODULE FUNCTIONS
	
	/** Function name: getModules
	 *
	 * This function returns the available Uran modules
	 * from the database.
	 *
	 * @return array of Module
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getModules(){
		$getModules = DB::table('modules')
			->orderBy('id', 'asc')
			->get();
		$modules = [];
		foreach($getModules as $module){
			array_push($modules, new Module($module->id, $module->name));
		}
		return $modules;
	}
	
	/** Function name: getModuleById
	 *
	 * This function returns the Uran module
	 * from the database based on the requested identifier.
	 *
	 * @param int $moduleId - module identifier
	 * @return Module|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getModuleById($moduleId){
		$module = DB::table('modules')
			->where('id', '=', $moduleId)
			->first();
		return $module === null ? null : new Module($module->id, $module->name);
	}
	
	/** Function name: getActiveModules
	 *
	 * This function returns the active Uran modules
	 * from the database.
	 *
	 * @return array of Module
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getActiveModules(){
		$getModules = DB::table('active_modules')
			->join('modules', 'modules.id', '=', 'active_modules.module_id')
			->orderBy('modules.id', 'asc')
			->get();
		$modules = [];
		foreach($getModules as $module){
			array_push($modules, new Module($module->id, $module->name, true));
		}
		return $modules;
	}
	
	/** Function name: getModulesLeftJoinedToActives
	 *
	 * This function returns the available Uran modules
	 * left joined to the active modules from the database.
	 *
	 * @return array of Module
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getModulesLeftJoinedToActives(){
		$getModules = DB::table('modules')
			->leftJoin('active_modules', 'active_modules.module_id', '=', 'modules.id')
			->orderBy('modules.id', 'asc')
			->get();
		$modules = [];
		foreach($getModules as $module){
			array_push($modules, new Module($module->id, $module->name, $module->module_id !== null));
		}
		return $modules;
	}
	
	/** Function name: getActiveModuleById
	 *
	 * This function returns the active Uran module
	 * from the database based on the requested identifier.
	 *
	 * @param int $moduleId - module identifier
	 * @return Module|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getActiveModuleById($moduleId){
		$module = DB::table('active_modules')
			->join('modules', 'modules.id', '=', 'active_modules.module_id')
			->where('module_id', '=', $moduleId)
			->first();
		return $module === null ? null : new Module($module->id, $module->name);
	}
	
	/** Function name: getActiveModuleByName
	 *
	 * This function returns the active Uran module
	 * from the database based on the requested module name.
	 *
	 * @param text $moduleName - module name
	 * @return Module|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getActiveModuleByName($moduleName){
		$module = DB::table('active_modules')
			->join('modules', 'modules.id', '=', 'active_modules.module_id')
			->where('modules.name','LIKE', $moduleName)
			->first();
		return $module === null ? null : new Module($module->id, $module->name);
	}
	
	/** Function name: activateModulById
	 *
	 * This function activates the requested module.
	 *
	 * @param text $moduleId - module name
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function activateModulById($moduleId){
		DB::table('active_modules')
			->insert([
					'module_id' => $moduleId
			]);
	}

	/** Function name: deactivateModuleById
	 *
	 * This function deactivates the requested module.
	 *
	 * @param text $moduleId - module name
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function deactivateModuleById($moduleId){
		DB::table('active_modules')
			->where('module_id', '=', $moduleId)
			->delete();
	}
	
//PERMISSIONS

	/** Function name: getPermissions
	 *
	 * This function returns all of the
	 * available permissions.
	 *
	 * @return array of Permission
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getPermissions(){
		$getPermissions = DB::table('permissions')
			->orderBy('id', 'asc')
			->get();
		$permissions = [];
		foreach($getPermissions as $permission){
			array_push($permissions, new Permission($permission->id, $permission->permission_name, $permission->description));
		}
		return $permissions;
	}
	
	/** Function name: getPermissionById
	 *
	 * This function returns a permission
	 * for the requested identifier.
	 *
	 * @param int $permissionId - permission identifier
	 * @return Permission|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getPermissionById($permissionId){
		$permission = DB::table('permissions')
			->where('permissions.id', '=', $permissionId)
			->first();
		return $permission === null ? null : new Permission($permission->id, $permission->permission_name, $permission->description);
	}
	
	/** Function name: getPermissionByName
	 *
	 * This function returns a permission
	 * for the requested name.
	 *
	 * @param int $permissionName - name of a permission
	 * @return Permission|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getPermissionByName($permissionName){
		$permission = DB::table('permissions')
			->where('permission_name', 'LIKE', $permissionName)
			->first();
		return $permission === null ? null : new Permission($permission->id, $permission->permission_name, $permission->description);
	}
	
	/** Function name: getPermissionGroup
	 *
	 * This function returns the requested
	 * permission group.
	 * 
	 * @param int $id - identifier of a group
	 * @return PermissionGroup|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getPermissionGroup($id){
		$group = DB::table('groups')
			->where('id', '=', $id)
			->first();
		if($group !== null){
			$rawPermissions = DB::table('permissions')
				->join('group_permissions', 'group_permissions.permission_id', '=', 'permissions.id')
				->where('group_permissions.group_id', '=', $group->id)
				->select('permissions.*')
				->get();
			$permissions = [];
			foreach($rawPermissions as $permission){
				$permissions[] = new Permission($permission->id, $permission->permission_name, $permission->description);
			}
			return new PermissionGroup($group->id, $group->group_name, $permissions);
		}else{
			return null;
		}
	}
	
	/** Function name: getPermissionGroups
	 *
	 * This function returns the available
	 * permission groups.
	 *
	 * @return array of PermissionGroup
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getPermissionGroups(){
		$groupsRaw = DB::table('groups')
			->get();
		$groups = [];
		foreach($groupsRaw as $group){
			$rawPermissions = DB::table('permissions')
				->join('group_permissions', 'group_permissions.permission_id', '=', 'permissions.id')
				->where('group_permissions.group_id', '=', $group->id)
				->select('permissions.*')
				->get();
			$permissions = [];
			foreach($rawPermissions as $permission){
				$permissions[] = new Permission($permission->id, $permission->permission_name, $permission->description);
			}
			$groups[] = new PermissionGroup($group->id, $group->group_name, $permissions);
		}
		return $groups;
	}
	
	/** Function name: addGroupPermission
	 *
	 * This function adds a new permission to a group.
	 *
	 * @param int $groupId - permission group identifier
	 * @param int $permissionId - permission identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addGroupPermission($groupId, $permissionId){
		DB::table('group_permissions')
			->insert([
				'permission_id' => $permissionId,
				'group_id' => $groupId
			]);
	}
	
	/** Function name: deleteGroupPermission
	 *
	 * This function removes an existing permission from a group.
	 * 
	 * @param int $groupId - permission group identifier
	 * @param int $permissionId - permission identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function deleteGroupPermission($groupId, $permissionId){
		DB::table('group_permissions')
			->where('group_id', '=', $groupId)
			->where('permission_id', '=', $permissionId)
			->delete();
	}
	
}
