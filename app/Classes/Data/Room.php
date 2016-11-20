<?php

namespace App\Classes\Data;

/** Class name: Room
 *
 * This class stores a dormitory Room.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Room{

	// PRIVATE DATA
	private $id;
	private $room_number;
	private $max_collegist_count;
	private $floor;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the Room class.
	 *
	 * @param int $id - room identifier
	 * @param string $room_number - room number
	 * @param int $max_collegist_count - maximum collegist count to live in the room
	 * @param int $floor - floor
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $room_number, int $max_collegist_count, int $floor){
		$this->id = $id;
		$this->room_number = $room_number;
		$this->max_collegist_count = $max_collegist_count;
		$this->floor = $floor;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the room.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: roomNumber
	 *
	 * This is the getter for room_number.
	 *
	 * @return string - The number of the room.
	 */
	public function roomNumber() : string{
		return $this->room_number;
	}
	
	/** Function name: maxCollegistCount
	 *
	 * This is the getter for max_collegist_count.
	 *
	 * @return int - The maximum collegist count of the room.
	 */
	public function maxCollegistCount() : int{
		return $this->max_collegist_count;
	}
	
	/** Function name: floor
	 *
	 * This is the getter for floor.
	 *
	 * @return int - The floor of the room.
	 */
	public function floor() : int{
		return $this->floor;
	}

}