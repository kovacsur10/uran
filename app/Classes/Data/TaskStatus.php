<?php

namespace App\Classes\Data;

/** Class name: TaskStatus
 *
 * This class stores a task TaskStatus.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class TaskStatus{

	// PRIVATE DATA
	private $id;
	private $status_name;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the TaskStatus class.
	 *
	 * @param int $id - status code
	 * @param string $name - status name
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name){
		$this->id = $id;
		$this->status_name = $name;
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
	 * This is the getter for status_name.
	 *
	 * @return string - The name of the status.
	 */
	public function name() : string{
		return $this->status_name;
	}

}