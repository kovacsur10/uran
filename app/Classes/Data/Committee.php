<?php

namespace App\Classes\Data;

/** Class name: Committee
 *
 * This class stores a record committee.
 *
 *@author Norbert Luksa <norbert.luksa@gmail.com>
 *
 */
class Committee{

	// PRIVATE DATA
	private $id;
	private $committee_name;
	private $isActive;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the Committee class.
	 *
	 * @param int $id - type identifier
	 * @param string $name - type name
	 * @param bool $isActive - is the committee active
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function __construct(int $id, string $name, bool $isActive){
		$this->id = $id;
		$this->committee_name = $name;
		$this->isActive = $isActive;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the committee.
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for committee_name.
	 *
	 * @return string - The name of the committee.
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function name() : string{
		return $this->committee_name;
	}

	/** Function name: isActive
	 *
	 * This is the getter for isActive.
	 *
	 * @return bool - true if the committee is active.
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function isActive() : bool{
		return $this->isActive;
	}
}