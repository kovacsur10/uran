<?php

namespace App\Classes\Layout;

/* Class name: Errors
 *
 * This class handles the custom
 * error indication between the
 * controllers and views (Request).
 *
 * Functionality:
 * 		- stores errors and old values
 */
class Errors{

// PRIVATE DATA
	
	private $errors;
	private $old;
	
// PUBLIC FUNCTIONS
	
	/* Function name: __construct
	 * Input: -
	 * Output: -
	 *
	 * This is the constructor for the Errors class.
	 */
	public function __construct(){
		$this->errors = [];
		$this->old = [];
	}

	/* Function name: add
	 * Input: 	$key (text) - error message identifier key
	 * 			$data (text) - error message
	 * Output: -
	 *
	 * This function saves an error
	 * message to the given key.
	 */
	public function add($key, $data){
		$this->errors[$key] = $data;
	}
	
	/* Function name: has
	 * Input: 	$key (text) - error message identifier key
	 * Output: -
	 *
	 * This function returns whether
	 * the requested key has an error
	 * message or not.
	 */
	public function has($key){
		return array_key_exists($key, $this->errors);
	}
	
	/* Function name: get
	 * Input: 	$key (text) - error message identifier key
	 * Output: error message (text|NULL)
	 *
	 * This function returns the
	 * error message connected to
	 * the requested key.
	 */
	public function get($key){
		if($this->has($key)){
			return $this->errors[$key];
		}else{
			return null;
		}
	}
	
	/* Function name: addOld
	 * Input: 	$key (text) - old value identifier key
	 * 			$data (text) - old value
	 * Output: -
	 *
	 * This function saves an old value
	 * to the given key.
	 */
	public function addOld($key, $data){
		$this->old[$key] = $data;
	}
	
	/* Function name: hasOld
	 * Input: 	$key (text) - old value identifier key
	 * Output: -
	 *
	 * This function returns whether
	 * the requested key has an old value or not.
	 */
	public function hasOld($key){
		return array_key_exists($key, $this->old);
	}
	
	/* Function name: getOld
	 * Input: 	$key (text) - old value identifier key
	 * Output: old value (text|NULL)
	 *
	 * This function returns the
	 * old value connected to
	 * the requested key.
	 */
	public function getOld($key){
		if($this->hasOld($key)){
			return $this->old[$key];
		}else{
			return null;
		}
	}
	
// PRIVATE FUNCTIONS
	
}
