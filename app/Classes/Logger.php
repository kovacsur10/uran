<?php

namespace App\Classes;

use Illuminate\Contracts\Session\Session;
use Carbon\Carbon;
use App\Persistence\P_General;

/** Class name: Logger
 *
 * This class handles the logging part
 * of the system.
 *
 * Functionality:
 * 		- error log to file
 * 		- normal log to database
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Logger{

// PUBLIC FUNCTIONS

	/** Function name: log
	 *
	 * This function writes log into the database.
	 * Severity of the log: normal log, not severe
	 *
	 * The old value and new value can be empty strings.
	 * If a value is changed according to these fields
	 * the old data can be recovered.
	 *
	 * A valid route should be added for debugging reasons.
	 *
	 * @param text $description - short description
	 * @param text $oldValue - if changed, the old value
	 * @param text $newValue - if changed, the new value
	 * @param text $route - route to the page
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function log($description, $oldValue, $newValue, $route){
		Logger::write($description, $oldValue, $newValue, $route, 1);
	}
	
	/** Function name: warning
	 *
	 * This function writes log into the database.
	 * Severity of the log: warning
	 *
	 * The old value and new value can be empty strings.
	 * If a value is changed according to these fields
	 * the old data can be recovered.
	 *
	 * A valid route should be added for debugging reasons.
	 *
	 * @param text $description - short description
	 * @param text $oldValue - if changed, the old value
	 * @param text $newValue - if changed, the new value
	 * @param text $route - route to the page
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function warning($description, $oldValue, $newValue, $route){
		Logger::write($description, $oldValue, $newValue, $route, 2);
	}
	
	/** Function name: error
	 *
	 * This function writes log into the database.
	 * Severity of the log: error, severe
	 *
	 * The old value and new value can be empty strings.
	 * If a value is changed according to these fields
	 * the old data can be recovered.
	 *
	 * A valid route should be added for debugging reasons.
	 * 
	 * @param text $description - short description
	 * @param text $oldValue - if changed, the old value
	 * @param text $newValue - if changed, the new value
	 * @param text $route - route to the page
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function error($description, $oldValue, $newValue, $route){
		Logger::write($description, $oldValue, $newValue, $route, 3);
	}
	
	/** Function name: error_log
	 *
	 * This function writes the error log into the 
	 * filesystem error log!
	 * 
	 * @param text $message - error message
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function error_log($message){
		error_log(Carbon::now().": ".$message."\n", 3, '/var/www/uran/storage/logs/uran_error.log');
	}
	
// PRIVATE FUNCTIONS
	
	/** Function name: write
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
	 * 
	 * @param text $description - short description
	 * @param text $oldValue - if changed, the old value
	 * @param text $newValue - if changed, the new value
	 * @param text $route - route to the page
	 * @param int $severity - severity of the log
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private static function write($description, $oldValue, $newValue, $route, $severity){
		try{
			$user = Auth::user();
			$user = $user !== null ? $user->id() : 0;
			P_General::writeIntoLog($description, $oldValue, $newValue, $route, $user, Carbon::now(), $severity);
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
	}
}
