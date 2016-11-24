<?php

namespace App\Classes\Data;

/** Class name: TaskType
 *
 * This class stores a task TaskType.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class TaskType{

	// PRIVATE DATA
	private $id;
	private $type_name;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the TaskType class.
	 *
	 * @param int $id - type identifier
	 * @param string $name - type name
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name){
		$this->id = $id;
		$this->type_name = $name;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the type.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for type_name.
	 *
	 * @return string - The name of the type.
	 */
	public function name() : string{
		return $this->type_name;
	}

}