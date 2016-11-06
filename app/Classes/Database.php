<?php

namespace App\Classes;

use App\Persistence as Persistence;
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
 * 		- beginTransaction
 * 		- rollback
 * 		- commit
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
			\Persistence\beginTransaction();
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Starting a new transaction was not successful! ".$ex->getMessage());
			throw $ex;
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
			\Persistence\rollback();
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Rollbacking the changes in a transaction was not successful! ".$ex->getMessage());
			throw $ex;
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
			\Persistence\commit();
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Committing the changes in a transaction was not successful! ".$ex->getMessage());
			throw $ex;
		}
	}
	
// PRIVATE FUNCTIONS
	
}
