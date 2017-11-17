<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use App\Classes\Layout\User;
use App\Classes\Notifications;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

/** Class name: ModuleController
 *
 * This controller is for handling the module system of the website.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class ModuleController extends Controller{

	/** Function name: show
	 *
	 * This function shows the available modules.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function show(){
		$layout = new LayoutData();
        return view('admin.modules', ["layout" => $layout]);
    }
	
    /** Function name: activate
     *
     * This function activetes a module.
     *
     * @param Request request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	public function activate(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('module_admin')){
			try{
				$layout->modules()->activate($request->module);
			}catch(\Exception $e){
				return view('errors.error', ["layout" => $layout,
											 "message_indicator" => 'error_at_module_activation',
											 "url" => '/admin/modules']);
			}
			$module = $layout->modules()->getById($request->module);
			Notifications::notifyAdminFromServer('module_admin', 'Module aktiválása', 'A(z) '.$module->name().' modul aktiválva lett!', 'admin/modules');
			return view('admin.modules', ["layout" => $layout]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
    /** Function name: deactivate
     *
     * This function inactivates a module.
     *
     * @param Request request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
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
