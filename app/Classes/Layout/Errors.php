<?php

namespace App\Classes\Layout;

/** Class name: Errors
 *
 * This class handles the custom
 * error indication between the
 * controllers and views (Request).
 *
 * Functionality:
 * 		- stores errors and old values
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>v
 */
class Errors{

// PRIVATE DATA
	
	private $errors;
	private $old;
	
// PUBLIC FUNCTIONS
	
	/** Function name: __construct
	 *
	 * This is the constructor for the Errors class.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(){
		$this->errors = [];
		$this->old = [];
	}

	/** Function name: add
	 *
	 * This function saves an error
	 * message to the given key.
	 * 
	 * @param text $key - error message identifier key
	 * @param text $data - error message
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function add($key, $data){
		$this->errors[$key] = $data;
	}
	
	/** Function name: has
	 *
	 * This function returns whether
	 * the requested key has an error
	 * message or not.
	 * 
	 * @param text $key - error message identifier key
	 * @return bool - has the key or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function has($key){
		return array_key_exists($key, $this->errors);
	}
	
	/** Function name: get
	 *
	 * This function returns the
	 * error message connected to
	 * the requested key.
	 * 
	 * @param text $key - error message identifier key
	 * @return text|null - error message
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function get($key){
		if($this->has($key)){
			return $this->errors[$key];
		}else{
			return null;
		}
	}
	
	/** Function name: addOld
	 *
	 * This function saves an old value
	 * to the given key.
	 * 
	 * @return text $key - old value identifier key
	 * @return text $data - old value
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addOld($key, $data){
		$this->old[$key] = $data;
	}
	
	/** Function name: hasOld
	 *
	 * This function returns whether
	 * the requested key has an old value or not.
	 * 
	 * @param text $key - old value identifier key
	 * @return bool - has the key or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function hasOld($key){
		return array_key_exists($key, $this->old);
	}
	
	/** Function name: getOld
	 *
	 * This function returns the old value
	 * connected to the requested key.
	 * 
	 * @param text $key - old value identifier key
	 * @return text|null - old value
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
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
