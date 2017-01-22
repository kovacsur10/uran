<?php

namespace App\Classes\Data;

/** Class name: EcnetUser
 *
 * This class stores an EcnetUser.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class EcnetUser{

	// PRIVATE DATA
	private $id;
	private $validTime;
	private $maxMacSlotCount;
	private $macAddresses;
	private $money;

	private $name;
	private $username;
	
	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the User class.
	 *
     * @param int $id - user's identifier
	 * @param string $name - user's name
	 * @param string $username - user's text identifier
	 * @param datetime $validTime - datetime, internet is valid until this date
	 * @param int $maxMacSlotCount - maximum MAC address slot count
	 * @param array $macSlotCount - MAC address slot count
	 * @param int $money - account status
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name, string $username, string $validTime, int $maxMacSlotCount, $macAddresses, int $money){
		$this->id = $id;
		$this->name = $name;
		$this->username = $username;
		$this->validTime = $validTime;
		$this->maxMacSlotCount = $maxMacSlotCount;
		$this->macAddresses = $macAddresses;
		$this->money = $money;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the user.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for name.
	 *
	 * @return string - The name of the user.
	 */
	public function name() : string{
		return $this->name;
	}
	
	/** Function name: username
	 *
	 * This is the getter for username.
	 *
	 * @return string - The username of the user.
	 */
	public function username() : string{
		return $this->username;
	}
	
	/** Function name: valid
	 *
	 * This is the getter for validTime.
	 *
	 * @return string - The password of the user.
	 */
	public function valid() : string{
		return $this->validTime;
	}
	
	/** Function name: money
	 *
	 * This is the getter for money.
	 *
	 * @return int - The account status of the user.
	 */
	public function money() : int{
		return $this->money;
	}
	
	/** Function name: maximumMacSlots
	 *
	 * This is the getter for maxMacSlotCount.
	 *
	 * @return int - Maximum count of MAC slots.
	 */
	public function maximumMacSlots() : int{
		return $this->maxMacSlotCount;
	}
	
	/** Function name: macAddresses
	 *
	 * This is the getter for macAddresses.
	 *
	 * @return int - Currently used MAC addresses.
	 */
	public function macAddresses(){
		return $this->macAddresses;
	}

}