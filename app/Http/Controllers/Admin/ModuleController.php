<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LayoutData;
use App\Classes\Notify;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DB;

class ModuleController extends Controller{

    public function show(){
		$layout = new LayoutData();
        return view('admin.modules', ["layout" => $layout]);
    }
	
	public function activate(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('module_admin')){
			try{
				DB::table('active_modules')
					->insert(['module_id' => $request->module]);
			}catch(\Illuminate\Database\QueryException $e){
				return view('errors.error', ["layout" => $layout,
											 "message" => 'Hiba a modul aktiválásakor!',
											 "url" => '/admin/modules']);
			}
			return view('admin.modules', ["layout" => $layout]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
	public function deactivate(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('module_admin')){
			DB::table('active_modules')
				->where('module_id', '=', $request->module)
				->delete();
			return view('admin.modules', ["layout" => $layout]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
}
