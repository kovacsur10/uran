<?php

namespace App\Classes\Data;

/** Class name: AssignmentTable
 *
 * This class stores an Assignment table for the rooms.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class AssignmentTable{

	// PRIVATE DATA
	private $id;
	private $name;
	private $active;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the AssignmentTable class.
	 *
	 * @param int $id - assignment table identifier
	 * @param string $name - assignment table name
	 * @param bool $selected - is this the currently active assigment table or not
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name, bool $selected){
		$this->id = $id;
		$this->name = $name;
		$this->active = $selected;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the assignment table.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for name.
	 *
	 * @return string - The name of the assignment table.
	 */
	public function name() : string{
		return $this->name;
	}
	
	/** Function name: active
	 *
	 * This is the getter for active.
	 *
	 * @return bool - The name of the assignment table.
	 */
	public function active() : bool{
		return $this->active;
	}

}