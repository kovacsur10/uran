<?php

namespace App\Persistence;

use DB;

/** Function name: getUsersWithPermission
 *
 * This function returns those users, who
 * have the requested permission.
 *
 * @param text $permission - permission text identifier
 * @return array of users
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
function getUsersWithPermission($permission){
	DB::table('users')
		->join('user_permissions', 'user_permissions.user_id', '=', 'users.id')
		->join('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
		->where('permissions.permission_name', 'LIKE', $permission)
		->where('registered', '=', true)
		->select('users.id as id')
		->get()
		->toArray();
}

/** Function name: updateUserLoginTime
 * 
 * This function updates the user's
 * last login time to the requested value.
 * 
 * @param text $username - user text identifier
 * @param datetime $datetime - login time
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
function updateUserLoginTime($username, $datetime){
	DB::table('users')
		->where('username', 'LIKE', $username)
		->update([
			'last_online' => $datetime
		]);
}

/** Function name: updateUserPassword
 * 
 * This function updates the requested user's
 * password for the given value.
 * 
 * @param text $username - user text identifier
 * @param text $password - new password
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
function updateUserPassword($username, $password){
	DB::table('users')
		->where('username', 'LIKE', $username)
		->update([
			'password' => password_hash($password, PASSWORD_DEFAULT)
		]);
}

/** Function name: getUserByUsername
 *
 * This function returns the user, who
 * has the requested username as text
 * identifier value.
 * 
 * @param text $username - user's text identifier
 * @return User|null
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
function getUserByUsername($username){
	return DB::table('users')
		->where('username', 'LIKE', $username)
		->where('registered', '=', 1)
		->first();
}