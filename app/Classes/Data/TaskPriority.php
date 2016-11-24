<?php

namespace App\Classes\Data;

/** Class name: TaskPriority
 *
 * This class stores a task TaskPriority.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class TaskPriority{

	// PRIVATE DATA
	private $id;
	private $priority_name;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the TaskPriority class.
	 *
	 * @param int $id - priority identifier
	 * @param string $name - name of priority
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name){
		$this->id = $id;
		$this->priority_name = $name;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the priority.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for priority_name.
	 *
	 * @return string - The name of the priority.
	 */
	public function name() : string{
		return $this->priority_name;
	}

}