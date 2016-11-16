<?php

namespace App\Classes\Data;

/** Class name: Faculty
 *
 * This class stores a university Faculty.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Faculty{

	// PRIVATE DATA
	private $id;
	private $name;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the Faculty class.
	 * 
	 * @param int $id - faculty identifier
	 * @param string $name - faculty name
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name){
		$this->id = $id;
		$this->name = $name;
	}
	
	/** Function name: id
	 * 
	 * This is the getter for id.
	 * 
	 * @return int - The identifier of the faculty.
	 */
	public function id() : int{
		return $this->id;
	}
	
	/** Function name: name
	 * 
	 * This is the getter for name.
	 * 
	 * @return string - The name of the faculty.
	 */
	public function name() : string{
		return $this->name;
	}
	
}