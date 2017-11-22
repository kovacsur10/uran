<?php 
namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use App\Classes\Database;
use App\Classes\Permissions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** Class name: GroupController
 *
 * This controller is for handling the permission groups of the website.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class GroupController extends Controller{

	/** Function name: showGroups
	 *
	 * This function shows the available permission groups.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showGroups(){
		$layout = new LayoutData();
		return view('admin.group.list', ["layout" => $layout]);
	}
	
	/** Function name: showUserModificationPage
	 *
	 * This function shows a user's permission group
	 * modifier page.
	 * 
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showUserModificationPage(Request $request){
		$layout = new LayoutData();
		return view('admin.group.user', ["layout" => $layout,
			"userid" => $request->user
		]);
	}

	/** Function name: showModifyPage
	 *
	 * This function shows the modification page for a specific permission group.
	 *
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showModifyPage(Request $request){
		$layout = new LayoutData();
		try{
			$group = $layout->permissions()->getPermissionGroup($request->group);
			return view('admin.group.modify', ["layout" => $layout,
				"group" => $group
			]);
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
				"message_indicator" => 'permissions.error_at_showing_permission_group_modification_page',
				"url" => '/admin/groups/list']);
		}
	}
	
	/** Function name: modify
	 *
	 * This function modifies the group permissions.
	 *
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function modify(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('permission_admin')){
			try{
				$layout->permissions()->setGroupPermissions($request->group, $request->permissions);
			}catch(\Exception $ex){
				return view('errors.error', ["layout" => $layout,
						"message_indicator" => 'permissions.error_at_setting_the_group_permissions',
						"url" => '/admin/groups/list']);
			}
			return view('success.success', ["layout" => $layout,
					"message_indicator" => 'permissions.success_at_setting_the_group_permissions',
					"url" => '/admin/groups/list']);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	/** Function name: modifyUser
	 *
	 * This function modifies a user's permission groups.
	 *
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function modifyUser(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('permission_admin')){
			try{
				$layout->permissions()->saveUserPermissionGroups($request->user, $request->groups);
			}catch(\Exception $ex){
				return view('errors.error', ["layout" => $layout,
						"message_indicator" => 'permissions.error_at_setting_the_group_permissions',
						"url" => '/admin/groups/list']);
			}
			return view('success.success', ["layout" => $layout,
					"message_indicator" => 'permissions.success_at_setting_the_group_permissions',
					"url" => '/admin/groups/list']);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	/** Function name: getUsersWithGroup
	 *
	 * This function shows a list of the users, who have the given group.
	 *
	 * @param Request request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getUsersWithGroup(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('permission_admin')){
			$users = $layout->permissions()->getUsersWithGroup($request->group);
			$group = $layout->permissions()->getPermissionGroup($request->group);
			return view('admin.group.listuserswithgroups', ["layout" => $layout,
					"users" => $users,
					"group" => $group]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
}
	
?>