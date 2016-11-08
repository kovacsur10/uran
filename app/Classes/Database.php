<?php

namespace App\Classes;

use App\Persistence\P_General;
use App\Classes\Logger;

/** Class name: Database
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
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Database{
	
// PUBLIC FUNCTIONS
	
	/** Function name: beginTransaction
	 *
	 * This function starts a new database transaction.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function beginTransaction(){
		try{
			P_General::beginTransaction();
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Starting a new transaction was not successful! ".$ex->getMessage());
			throw $ex;
		}
	}
	
	/** Function name: rollback
	 *
	 * This function rollback a database transaction.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function rollback(){
		try{
			P_General::rollback();
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Rollbacking the changes in a transaction was not successful! ".$ex->getMessage());
			throw $ex;
		}
	}
	
	/** Function name: commit
	 *
	 * This function commits a database transaction.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function commit(){
		try{
			P_General::commit();
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Committing the changes in a transaction was not successful! ".$ex->getMessage());
			throw $ex;
		}
	}
	
// PRIVATE FUNCTIONS
	
}
