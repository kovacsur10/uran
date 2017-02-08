<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use App\Classes\Notifications;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** Class name: ModuleController
 *
 * This controller is for handling the module system of the website.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class DefaultPermissionsController extends Controller{

	/** Function name: show
	 *
	 * This function shows the current default permissions.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function show(){
		$layout = new LayoutData();
        return view('admin.defaultpermissions', ["layout" => $layout]);
    }
	
    /** Function name: setGuest
     *
     * This function sets the default guest permissions table.
     *
     * @param Request request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function setGuest(Request $request){
		$layout = new LayoutData();
		$permissions = [];
		
		try{
			$layout->permissions()->setDefaults('guest', $request->permissions);
			Notifications::notifyAdminFromServer('permission_admin', 'Alapértelmezett jogok', 'A vendégekre vonatkozó alapértelmezett jogok megváltoztak!', 'admin/permissions/default');
			return view('admin.defaultpermissions', ["layout" => $layout]);
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_setting_the_permissions'),
										 "url" => '/admin/permissions/default']);
		}
    }
	
    /** Function name: setCollegist
     *
     * This function sets the default collegist permissions table.
     *
     * @param Request request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function setCollegist(Request $request){
		$layout = new LayoutData();
		$permissions = [];
		
		try{
			$layout->permissions()->setDefaults('collegist', $request->permissions);
			Notifications::notifyAdminFromServer('permission_admin', 'Alapértelmezett jogok', 'A collegistákra vonatkozó alapértelmezett jogok megváltoztak!', 'admin/permissions/default');
			return view('admin.defaultpermissions', ["layout" => $layout]);
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_setting_the_permissions'),
										 "url" => '/admin/permissions/default']);
		}
    }
	
}
