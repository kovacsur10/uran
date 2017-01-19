<?php

namespace App\Classes\Data;

/** Class name: MacSlotOrder
 *
 * This class stores a order for a new MAC address slot.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class MacSlotOrder{

	// PRIVATE DATA
	private $id;
	private $reason;
	private $orderTime;
	private $username;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the MacSlotOrder class.
	 *
	 * @param int $id - identifier of the order
	 * @param string $reason - reason of the order
	 * @param string $orderTime - the timestamp of the order
	 * @param string $username - the requester
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $reason, string $orderTime, string $username){
		$this->id = $id;
		$this->reason = $reason;
		$this->orderTime = $orderTime;
		$this->username = $username;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the order.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: reason
	 *
	 * This is the getter for reason.
	 *
	 * @return string - The reason of the order.
	 */
	public function reason() : string{
		return $this->reason;
	}

	/** Function name: time
	 *
	 * This is the getter for the orderTime.
	 *
	 * @return string - The request time of the order.
	 */
	public function time() : string{
		return $this->orderTime;
	}
	
	/** Function name: username
	 *
	 * This is the getter for username.
	 *
	 * @return string - The username of the requester.
	 */
	public function username() : string{
		return $this->username;
	}

}

?>