<?php

namespace App\Classes;

use DB;
use App\Classes\Logger;

/* Class name: Database
 *
 * This class handles the database wrapper
 * functions.
 *
 * Functionality:
 * 		- transaction handling
 * 
 * Functions that can throw exceptions:
 */
class Database{
	
// PUBLIC FUNCTIONS
	
	/* Function name: beginTransaction
	 * Input: -
	 * Output: -
	 *
	 * This function starts a new database transaction.
	 */
	public static function beginTransaction(){
		try{
			DB::beginTransaction();
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Starting a new transaction was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: rollback
	 * Input: -
	 * Output: -
	 *
	 * This function rollback a database transaction.
	 */
	public static function rollback(){
		try{
			DB::rollback();
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Rollbacking the changes in a transaction was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: commit
	 * Input: -
	 * Output: -
	 *
	 * This function commits a database transaction.
	 */
	public static function commit(){
		try{
			DB::commit();
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Committing the changes in a transaction was not successful! ".$ex->getMessage());
		}
	}
	
// PRIVATE FUNCTIONS
	
}
