<?php

namespace App\Classes;

use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Persistence as Persistence;

/** Class name: Logger
 *
 * This class handles the logging part
 * of the system.
 *
 * Functionality:
 * 		- error log to file
 * 		- normal log to database
 * 
 * Functions that can throw exceptions:
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Logger{

// PUBLIC FUNCTIONS

	/* Function name: log
	 * Input: 	$description (text) - short description
	 * 			$oldValue (text) - if changed, the old value
	 * 			$newValue (text) - if changed, the new value
	 * 			$route (text) - route to the page
	 * Output: -
	 *
	 * This function writes log into the database.
	 * Severity of the log: normal log, not severe
	 * 
	 * The old value and new value can be empty strings.
	 * If a value is changed according to these fields
	 * the old data can be recovered.
	 * 
	 * A valid route should be added for debugging reasons.
	 */
	public static function log($description, $oldValue, $newValue, $route){
		Logger::write($description, $oldValue, $newValue, $route, 1);
	}
	
	/* Function name: warning
	 * Input: 	$description (text) - short description
	 * 			$oldValue (text) - if changed, the old value
	 * 			$newValue (text) - if changed, the new value
	 * 			$route (text) - route to the page
	 * Output: -
	 *
	 * This function writes log into the database.
	 * Severity of the log: warning
	 *
	 * The old value and new value can be empty strings.
	 * If a value is changed according to these fields
	 * the old data can be recovered.
	 *
	 * A valid route should be added for debugging reasons.
	 */
	public static function warning($description, $oldValue, $newValue, $route){
		Logger::write($description, $oldValue, $newValue, $route, 2);
	}
	
	/* Function name: error
	 * Input: 	$description (text) - short description
	 * 			$oldValue (text) - if changed, the old value
	 * 			$newValue (text) - if changed, the new value
	 * 			$route (text) - route to the page
	 * Output: -
	 *
	 * This function writes log into the database.
	 * Severity of the log: error, severe
	 *
	 * The old value and new value can be empty strings.
	 * If a value is changed according to these fields
	 * the old data can be recovered.
	 *
	 * A valid route should be added for debugging reasons.
	 */
	public static function error($description, $oldValue, $newValue, $route){
		Logger::write($description, $oldValue, $newValue, $route, 3);
	}
	
	/* Function name: error_log
	 * Input: 	$message (text) - error message
	 * Output: -
	 *
	 * This function writes the error log into the 
	 * filesystem error log!
	 */
	public static function error_log($message){
		error_log(Carbon::now().": ".$message."\n", 3, '/var/log/uran_error.log');
	}
	
// PRIVATE FUNCTIONS
	
	/* Function name: write
	 * Input: 	$description (text) - short description
	 * 			$oldValue (text) - if changed, the old value
	 * 			$newValue (text) - if changed, the new value
	 * 			$route (text) - route to the page
	 * 			$severity (int) - severity of the log
	 * Output: -
	 *
	 * This function writes log into the database.
	 * Severity of the log can be:
	 * 		1 - normal log
	 * 		2 - warning
	 * 		3 - error
	 *
	 * The old value and new value can be empty strings.
	 * If a value is changed according to these fields
	 * the old data can be recovered.
	 *
	 * A valid route should be added for debugging reasons.
	 */
	private static function write($description, $oldValue, $newValue, $route, $severity){
		try{
			$user = Session::has('user') ? Session::get('user')->id : 0;
			\Persistence\writeIntoLog($description, $oldValue, $newValue, $route, $user, Carbon::now(), $severity);
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'logs' was not successful! ".$ex->getMessage());
		}
	}
}
