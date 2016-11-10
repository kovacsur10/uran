<?php

namespace App\Classes\Layout;

use App\Persistence\P_General;
use App\Classes\Logger;

/** Class name: Modules
 *
 * This class handles the system modules.
 *
 * Functionality:
 * 		- module activation and deactivation
 * 		- module getter functions
 * 
 * Functions that can throw exceptions:
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Modules{

// PUBLIC FUNCTIONS

	/** Function name: get
	 *
	 * This function returns the available 
	 * modules.
	 * 
	 * @return array of available modules
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function get(){
		try{
			$modules = P_General::getModules();
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'modules' was not successful! ".$ex->getMessage());
			$modules = [];
		}
		return $modules;
	}
	
	/** Function name: getById
	 *
	 * This function returns a module
	 * based on the requested identifier.
	 * 
	 * @param int $moduleId - identifier of a module
	 * @return Module data
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getById($moduleId){
		try{
			$module = P_General::getModuleById($moduleId);
		}catch(Exception $ex){
			$module = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'modules' was not successful! ".$ex->getMessage());
		}
		return $module;
	}
	
	/** Function name: isActivatedById
	 *
	 * This function returns the status
	 * of the requested module. It returns
	 * true if the module is in active state,
	 * otherwise the return value is false.
	 * 
	 * @param int $moduleId - identifier of a module
	 * @return bool - activation status
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function isActivatedById($moduleId){
		try{
			$ret = P_General::getActiveModuleById($moduleId);
		}catch(Exception $ex){
			$ret = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'active_modules' was not successful! ".$ex->getMessage());
		}
		return $ret !== null;
	}
	
	/** Function name: isActivatedByName
	 *
	 * This function returns the status
	 * of the requested module. It returns
	 * true if the module is in active state,
	 * otherwise the return value is false.
	 * 
	 * @param text $moduleName - name of a module
	 * @return bool - activation status
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function isActivatedByName($moduleName){
		try{
			$ret = P_General::getActiveModuleByName($moduleName);
		}catch(Exception $ex){
			$ret = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'active_modules' joined to 'modules' was not successful! ".$ex->getMessage());
		}
		return $ret !== null;
	}
	
	/** Function name: getActives
	 *
	 * This function returns an array
	 * of the activated modules.
	 * 
	 * @return array of Modules
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getActives(){
		try{
			$ret = P_General::getActiveModules();
		}catch(Exception $ex){
			$ret = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'modules' joined to 'active_modules' was not successful! ".$ex->getMessage());
		}
		return $ret;
	}
	
	/** Function name: getInactives
	 *
	 * This function returns an array
	 * of the deactivated modules.
	 * 
	 * @return array of Modules
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getInactives(){
		try{
			$modules = P_General::getModulesLeftJoinedToActives();
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
	
	/** Function name: activate
	 *
	 * This function sets the status
	 * of the requested module to active.
	 * 
	 * @param int $moduleId - identifier of a module
	 * @return bool - successfully updated
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function activate($moduleId){
		try{
			P_General::activateModulById($moduleId);
			$successful = true;
		}catch(Exception $ex){
			$successful = false;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'active_modules' was not successful! ".$ex->getMessage());
		}
		return $successful;
	}
	
	/** Function name: deactivate
	 *
	 * This function sets the status
	 * of the requested module to inactive.
	 * 
	 * @param int $moduleId - identifier of a module
	 * @return bool - successfully updated
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function deactivate($moduleId){
		try{
			P_General::deactivateModuleById($moduleId);
			$successful = true;
		}catch(Exception $ex){
			$successful = false;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'active_modules' was not successful! ".$ex->getMessage());
		}
		return $successful;
	}
	
// PRIVATE FUNCTIONS
	
}
