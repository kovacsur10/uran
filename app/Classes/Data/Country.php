<?php

namespace App\Classes\Data;

/** Class name: Country
 *
 * This class stores a Country.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Country{

	// PRIVATE DATA
	private $id;
	private $name;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the Country class.
	 *
	 * @param string $id - country identifier
	 * @param string $name - country name
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(string $id, string $name){
		$this->id = $id;
		$this->name = $name;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return string - The identifier of the country.
	 */
	public function id() : string{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for name.
	 *
	 * @return string - The name of the country.
	 */
	public function name() : string{
		return $this->name;
	}

}