<?php

namespace App\Classes\Data;

/** Class name: Workshop
 *
 * This class stores a dormitory Workshop.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Workshop{

	// PRIVATE DATA
	private $id;
	private $name;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the Workshop class.
	 *
	 * @param int $id - workshop identifier
	 * @param string $name - workshop name
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
	 * @return int - The identifier of the workshop.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for name.
	 *
	 * @return string - The name of the workshop.
	 */
	public function name() : string{
		return $this->name;
	}

}