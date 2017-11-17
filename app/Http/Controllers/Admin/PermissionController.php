<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use App\Classes\Database;
use App\Classes\Permissions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** Class name: PermissionController
 *
 * This controller is for handling the permissions of the website.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class PermissionController extends Controller{

	/** Function name: showPermissions
	 *
	 * This function shows the available permissions and the users.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function showPermissions(){
		$layout = new LayoutData();
        return view('admin.permissions', ["layout" => $layout]);
    }
	
    /** Function name: modifyPermissions
     *
     * This function shows the user's permissions.
     * 
     * @param Request request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function modifyPermissions(Request $request){
		$layout = new LayoutData();
        return view('admin.permission_modify', ["layout" => $layout,
												"userid" => $request->user]);
    }
	
    /** Function name: setPermissions
     *
     * This function updates the permissions of a user.
     * 
     * @param Request request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function setPermissions(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('permission_admin')){
			try{
				Database::transaction(function() use($request, $layout){
					$layout->permissions()->removeAll($request->user);
					if($request->permissions !== null){
						foreach($request->permissions as $permission){
							$layout->permissions()->setPermissionForUser($request->user, $permission);
						}
					}
				});
			}catch(\Exception $ex){
				return view('errors.error', ["layout" => $layout,
						"message_indicator" => 'permissions.error_at_setting_the_permissions',
						"url" => '/admin/permissions']);
			}
			return view('success.success', ["layout" => $layout,
					"message_indicator" => 'permissions.success_at_setting_the_permissions',
					"url" => '/admin/permissions']);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	/** Function name: getUsersWithPermission
	 *
	 * This function shows a list of the users, who have the given permission.
	 * 
	 * @param Request request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getUsersWithPermission(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('permission_admin')){
			$users = $layout->permissions()->getUsersWithPermission($request->permission);
			$permission = $layout->permissions()->getByName($request->permission);
			return view('admin.listuserswithpermission', ["layout" => $layout,
														  "users" => $users,
														  "permission" => $permission->name()." (".$permission->description().")"]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
}
