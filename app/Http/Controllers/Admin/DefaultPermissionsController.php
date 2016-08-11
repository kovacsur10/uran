<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class DefaultPermissionsController extends Controller{

	public function show(){
		$layout = new LayoutData();
        return view('admin.defaultpermissions', ["layout" => $layout]);
    }
	
	public function setGuest(Request $request){
		$layout = new LayoutData();
		$permissions = [];
		
		if($layout->permissions()->setDefaults('guest', $request->permissions) === 0){
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
			return view('admin.defaultpermissions', ["layout" => $layout]);
		}else{
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_setting_the_permissions'),
										 "url" => '/admin/permissions/default']);
		}
    }
	
}
