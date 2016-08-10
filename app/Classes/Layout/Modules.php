<?php

namespace App\Classes\Layout;

use App\Classes\Layout\User;
use Carbon\Carbon;
use DB;

class Modules{

	public function get(){
		$moduls = DB::table('modules')
			->orderBy('id', 'asc')
			->get();
		return $moduls == null ? [] : $moduls;
	}
	
	public function isActivatedById($id){
		$ret = DB::table('active_modules')
			->where('module_id', '=', $id)
			->first();
		return $ret == null ? false : true;;
	}
	
	public function isActivatedByName($name){
		$ret = DB::table('active_modules')
			->join('modules', 'modules.id', '=', 'active_modules.module_id')
			->where('modules.name','LIKE', $name)
			->first();
		return $ret == null ? false : true;;
	}
	
	public function getActives(){
		$moduls = DB::table('active_modules')
			->join('modules', 'modules.id', '=', 'active_modules.module_id')
			->get();
		return $moduls == null ? [] : $moduls;
	}
	
	public function getInactives(){
		$modules = DB::table('modules')
			->leftJoin('active_modules', 'active_modules.module_id', '=', 'modules.id')
			->get();
		$inactives = [];
		foreach($modules as $module){
			if($module->module_id == null)
				array_push($inactives, $module);
		}
		return $inactives;
	}
	
}
