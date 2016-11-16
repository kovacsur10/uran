<?php

namespace App\Classes\Data;

/** Class name: StatusCode
 *
 * This class stores a user StatusCode.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class StatusCode{

	// PRIVATE DATA
	private $id;
	private $status_name;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the StatusCode class.
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

	/** Function name: statusName
	 *
	 * This is the getter for name.
	 *
	 * @return string - The name of the status.
	 */
	public function statusName() : string{
		return $this->status_name;
	}

}