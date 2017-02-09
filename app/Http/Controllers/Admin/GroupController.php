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

	/** Function name: showModifyPage
	 *
	 * This function shows the modification page for a specific permission group.
	 *
	 * $param Request $request
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
				"message" => $layout->language('error_at_showing_permission_group_modification_page'), //TODO
				"url" => '/admin/groups/list']);
		}
	}
}
	
?>