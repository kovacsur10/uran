<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use App\Classes\Layout\User;
use App\Classes\Notify;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DefaultPermissionsController extends Controller{

	public function show(){
		$layout = new LayoutData();
        return view('admin.defaultpermissions', ["layout" => $layout]);
    }
	
	public function setGuest(Request $request){
		$layout = new LayoutData();
		$permissions = [];
		
		if($layout->permissions()->setDefaults('guest', $request->permissions) === 0){
			Notify::notifyAdminFromServer('permission_admin', 'Alapértelmezett jogok', 'A vendégekre vonatkozó alapértelmezett jogok megváltoztak!', 'admin/permissions/default');
			return view('admin.defaultpermissions', ["layout" => $layout]);
		}else{
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_setting_the_permissions'),
										 "url" => '/admin/permissions/default']);
		}
    }
	
	public function setCollegist(Request $request){
		$layout = new LayoutData();
		$permissions = [];
		
		if($layout->permissions()->setDefaults('collegist', $request->permissions) === 0){
			Notify::notifyAdminFromServer('permission_admin', 'Alapértelmezett jogok', 'A collegistákra vonatkozó alapértelmezett jogok megváltoztak!', 'admin/permissions/default');
			return view('admin.defaultpermissions', ["layout" => $layout]);
		}else{
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_setting_the_permissions'),
										 "url" => '/admin/permissions/default']);
		}
    }
	
}
