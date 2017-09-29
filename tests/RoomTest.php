<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\Room;

/** Class name: RoomTest
 *
 * This class is the PHPUnit test for the Data\Room data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class RoomTest extends BrowserKitTestCase
{
	use DatabaseTransactions;

	/** Function name: test_room
	 *
	 * This function is testing the Room data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_room(){
		$room = new Room(1, "alma", 2, 3);
		$this->assertEquals(1, $room->id());
		$this->assertEquals("alma", $room->roomNumber());
		$this->assertEquals(2, $room->maxCollegistCount());
		$this->assertEquals(3, $room->floor());
		
		$room = new Room("1", 33, "2", "3");
		$this->assertEquals(1, $room->id());
		$this->assertEquals("33", $room->roomNumber());
		$this->assertEquals(2, $room->maxCollegistCount());
		$this->assertEquals(3, $room->floor());
	}

	/** Function name: test_room_attr
	 *
	 * This function is testing the Room data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_room_attr(){
		$this->assertClassHasAttribute('id', Room::class);
		$this->assertClassHasAttribute('room_number', Room::class);
		$this->assertClassHasAttribute('max_collegist_count', Room::class);
		$this->assertClassHasAttribute('floor', Room::class);
	}
}