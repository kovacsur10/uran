<?php

namespace App\Classes\Data;

/** Class name: Module
 *
 * This class stores a Module.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Module{

	// PRIVATE DATA
	private $id;
	private $name;
	private $active;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the Module class.
	 *
	 * @param int $id - module identifier
	 * @param string $name - module name
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name, bool $active = null){
		$this->id = $id;
		$this->name = $name;
		$this->active = $active;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the module.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for name.
	 *
	 * @return string - The name of the module.
	 */
	public function name() : string{
		return $this->name;
	}
	
	/** Function name: isActive
	 *
	 * This is the getter for name.
	 *
	 * @return bool|null - The name of the module.
	 */
	public function isActive(){
		return $this->active;
	}

}