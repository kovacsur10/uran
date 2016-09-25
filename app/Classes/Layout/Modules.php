<?php

namespace App\Classes\Layout;

use DB;

class Modules{

// PUBLIC FUNCTIONS

	public function get(){
		return DB::table('modules')
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
	public function getById($id){
		return DB::table('modules')
			->where('id', '=', $id)
			->first();
	}
	
	public function isActivatedById($id){
		$ret = DB::table('active_modules')
			->where('module_id', '=', $id)
			->first();
		return $ret !== null;
	}
	
	public function isActivatedByName($name){
		$ret = DB::table('active_modules')
			->join('modules', 'modules.id', '=', 'active_modules.module_id')
			->where('modules.name','LIKE', $name)
			->first();
		return $ret !== null;
	}
	
	public function getActives(){
		return DB::table('active_modules')
			->join('modules', 'modules.id', '=', 'active_modules.module_id')
			->get()
			->toArray();
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
	
	public function activate($moduleId){
		DB::table('active_modules')
			->insert([
				'module_id' => $moduleId
			]);
	}
	
	public function deactivate($moduleId){
		DB::table('active_modules')
			->where('module_id', '=', $moduleId)
			->delete();
	}
	
}
