<?php

namespace App\Persistence;

use DB;

/* Function name: getUsersWithPermission
 * Input: $permission (text) - permission text identifier
 * Output: array of users
 *
 * This function returns those users, who
 * have the requested permission.
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