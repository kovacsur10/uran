<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use App\Classes\Layout\User;
use App\Classes\Notifications;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ModuleController extends Controller{

    public function show(){
		$layout = new LayoutData();
        return view('admin.modules', ["layout" => $layout]);
    }
	
	public function activate(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('module_admin')){
			try{
				$layout->modules()->activate($request->module);
			}catch(\Exception $e){
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('error_at_module_activation'),
											 "url" => '/admin/modules']);
			}
			$module = $layout->modules()->getById($request->module);
			Notifications::notifyAdminFromServer('module_admin', 'Module aktiválása', 'A(z) '.$module->name().' modul aktiválva lett!', 'admin/modules');
			return view('admin.modules', ["layout" => $layout]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
	public function deactivate(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('module_admin')){
			try{
				$layout->modules()->deactivate($request->module);
			}catch(\Exception $ex){
				return view('errors.error', ["layout" => $layout,
						"message" => $layout->language('error_at_module_deactivation'),
						"url" => '/admin/modules']);
			}
			$module = $layout->modules()->getById($request->module);
			Notifications::notifyAdminFromServer('module_admin', 'Modul deaktiválása', 'A(z) '.$module->name().' modul deaktiválva lett!', 'admin/modules');
			return view('admin.modules', ["layout" => $layout]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
}
