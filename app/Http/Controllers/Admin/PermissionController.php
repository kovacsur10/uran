<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use App\Classes\Notifications;
use App\Classes\Database;
use App\Classes\Permissions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PermissionController extends Controller{

    public function showPermissions(){
		$layout = new LayoutData();
        return view('admin.permissions', ["layout" => $layout]);
    }
	
	public function modifyPermissions(Request $request){
		$layout = new LayoutData();
        return view('admin.permission_modify', ["layout" => $layout,
												"userid" => $request->user]);
    }
	
	public function setPermissions(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('permission_admin')){
			$error = false;
			Database::beginTransaction(); //DATABASE TRANSACTION STARTS HERE
			$error = ($layout->permissions()->removeAll($request->user) != 0);
			if($request->permissions != null){
				foreach($request->permissions as $permission){
					$error = ($layout->permissions()->setPermissionForUser($request->user, $permission) != 0);
				}
			}
			
			if($error){
				Database::rollback();
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('error_at_setting_the_permissions'),
											 "url" => '/admin/permissions']);
			}else{
				Database::commit();
				Notifications::notify($layout->user(), $request->user, 'Megváltoztak a jogaid', 'Egy adminisztrátor módosította a jogaidat a rendszerben!', 'home');
				return view('admin.permissions', ["layout" => $layout]);
			} //DATABASE TRANSACTION ENDS HERE
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	public function getUsersWithPermission(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('permission_admin')){
			$users = $layout->permissions()->getUsersWithPermission($request->permission);
			$permission = $layout->permissions()->getByName($request->permission);
			return view('admin.listuserswithpermission', ["layout" => $layout,
														  "users" => $users,
														  "permission" => $permission->permission_name." (".$permission->description.")"]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
}
