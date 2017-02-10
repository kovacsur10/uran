<?php

namespace App\Classes\Data;

/** Class name: Permission
 *
 * This class stores a site Permission.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Permission{

	// PRIVATE DATA
	private $id;
	private $name;
	private $description;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the Permission class.
	 *
	 * @param int $id - status code
	 * @param string $name - status name
	 * @param string $description - description of the permission
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name, string $description){
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the status code.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for name.
	 *
	 * @return string - The name of the status.
	 */
	public function name() : string{
		return $this->name;
	}
	
	/** Function name: name
	 *
	 * This is the getter for the description.
	 *
	 * @return string - The name of the status.
	 */
	public function description() : string{
		return $this->description;
	}

	/** Function name: __toString
	 *
	 * This is for identifying as a string.
	 *
	 * @return string - The name identifier.
	 */
	public function __toString(){
		return $this->name;
	}
}