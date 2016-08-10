<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use App\Classes\Notify;
use App\Classes\Database;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DB;

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
			try{
				DB::table('user_permissions')
					->where('user_id', '=', $request->user)
					->delete();
			}catch(\Illuminate\Database\QueryException $e) {
				$error = true;
			}
			if($request->permissions != null){
				foreach($request->permissions as $permission){
					try{
						DB::table('user_permissions')
							->insert(['user_id' => $request->user, 'permission_id' => $permission]);
					}catch(\Illuminate\Database\QueryException $e) {
						$error = true;
					}
				}
			}
			
			if($error){
				Database::rollback();
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('error_at_setting_the_permissions'),
											 "url" => '/admin/permissions']);
			}else{
				Database::commit();
				return view('admin.permissions', ["layout" => $layout]);
			} //DATABASE TRANSACTION ENDS HERE
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	public function getUsersWithPermission(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('permission_admin')){
			$users = DB::table('permissions')
				->join('user_permissions', 'user_permissions.permission_id', '=', 'permissions.id')
				->join('users', 'users.id', '=', 'user_permissions.user_id')
				->where('permissions.id', '=', $request->permission)
				->select('users.id', 'users.name', 'users.username')
				->get();
			$permission = DB::table('permissions')
				->where('permissions.id', '=', $request->permission)
				->first();
			if($users == null)
				$users = [];
			return view('admin.listuserswithpermission', ["layout" => $layout,
														  "users" => $users,
														  "permission" => $permission->permission_name." (".$permission->description.")"]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
}
