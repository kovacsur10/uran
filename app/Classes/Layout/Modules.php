<?php

namespace App\Classes\Layout;

use DB;
use App\Classes\Logger;

/* Class name: Modules
 *
 * This class handles the system modules.
 *
 * Functionality:
 * 		- module activation and deactivation
 * 		- module getter functions
 * 
 * Functions that can throw exceptions:
 */
class Modules{

// PUBLIC FUNCTIONS

	/* Function name: get
	 * Input: -
	 * Output: array of available modules
	 *
	 * This function returns the available 
	 * modules.
	 */
	public function get(){
		try{
			$ret = DB::table('modules')
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'modules' was not successful! ".$ex->getMessage());
			$ret = [];
		}
		return $ret;
	}
	
	/* Function name: getById
	 * Input: $id (int) - identifier of a module
	 * Output: module data
	 *
	 * This function returns a module
	 * based on the requested identifier.
	 */
	public function getById($id){
		try{
			$ret = DB::table('modules')
				->where('id', '=', $id)
				->first();
		}catch(Exception $ex){
			$ret = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'modules' was not successful! ".$ex->getMessage());
		}
		return $ret;
	}
	
	/* Function name: isActivatedById
	 * Input: $id (int) - identifier of a module
	 * Output: bool (activation status)
	 *
	 * This function returns the status
	 * of the requested module. It returns
	 * true if the module is in active state,
	 * otherwise the return value is false.
	 */
	public function isActivatedById($id){
		try{
			$ret = DB::table('active_modules')
				->where('module_id', '=', $id)
				->first();
		}catch(Exception $ex){
			$ret = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'active_modules' was not successful! ".$ex->getMessage());
		}
		return $ret !== null;
	}
	
	/* Function name: isActivatedByName
	 * Input: $name (text) - name of a module
	 * Output: bool (activation status)
	 *
	 * This function returns the status
	 * of the requested module. It returns
	 * true if the module is in active state,
	 * otherwise the return value is false.
	 */
	public function isActivatedByName($name){
		try{
			$ret = DB::table('active_modules')
				->join('modules', 'modules.id', '=', 'active_modules.module_id')
				->where('modules.name','LIKE', $name)
				->first();
		}catch(Exception $ex){
			$ret = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'active_modules' joined to 'modules' was not successful! ".$ex->getMessage());
		}
		return $ret !== null;
	}
	
	/* Function name: getActives
	 * Input: -
	 * Output: array of modules
	 *
	 * This function returns an array
	 * of the activated modules.
	 */
	public function getActives(){
		try{
			$ret = DB::table('active_modules')
				->join('modules', 'modules.id', '=', 'active_modules.module_id')
				->get()
				->toArray();
		}catch(Exception $ex){
			$ret = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'modules' joined to 'active_modules' was not successful! ".$ex->getMessage());
		}
		return $ret;
	}
	
	/* Function name: getInactives
	 * Input: -
	 * Output: array of modules
	 *
	 * This function returns an array
	 * of the deactivated modules.
	 */
	public function getInactives(){
		try{
			$modules = DB::table('modules')
				->leftJoin('active_modules', 'active_modules.module_id', '=', 'modules.id')
				->get()
				->toArray();
		}catch(Exception $ex){
			$modules = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'modules' joined to 'active_modules' was not successful! ".$ex->getMessage());
		}
		$inactives = [];
		foreach($modules as $module){
			if($module->module_id === null){
				array_push($inactives, $module);
			}
		}
		return $inactives;
	}
	
	/* Function name: activate
	 * Input: $moduleId (int) - identifier of a module
	 * Output: bool (successfully updated)
	 *
	 * This function sets the status
	 * of the requested module to active.
	 */
	public function activate($moduleId){
		try{
			DB::table('active_modules')
				->insert([
					'module_id' => $moduleId
				]);
			$successful = true;
		}catch(Exception $ex){
			$successful = false;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'active_modules' was not successful! ".$ex->getMessage());
		}
		return $successful;
	}
	
	/* Function name: deactivate
	 * Input: $moduleId (int) - identifier of a module
	 * Output: bool (successfully updated)
	 *
	 * This function sets the status
	 * of the requested module to inactive.
	 */
	public function deactivate($moduleId){
		try{
			DB::table('active_modules')
				->where('module_id', '=', $moduleId)
				->delete();
			$successful = true;
		}catch(Exception $ex){
			$successful = false;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'active_modules' was not successful! ".$ex->getMessage());
		}
		return $successful;
	}
	
// PRIVATE FUNCTIONS
	
}
