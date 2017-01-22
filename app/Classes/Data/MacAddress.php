<?php

namespace App\Classes\Data;

/** Class name: MacAddress
 *
 * This class stores a MAC address.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class MacAddress{

	// PRIVATE DATA
	private $id;
	private $address;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the MacAddress class.
	 *
	 * @param int $id - MAC address id
	 * @param string $address - MAC address
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $address){
		$this->id = $id;
		$this->address = $address;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the MAC address.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: address
	 *
	 * This is the getter for address.
	 *
	 * @return string - The MAC address.
	 */
	public function address() : string{
		return $this->address;
	}

}