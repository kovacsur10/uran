<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\Rooms;

/** Class name: RoomsTest
 *
 * This class is the PHPUnit test for the Layout\Rooms model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class RoomsTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_class
	 *
	 * This function is testing the class itself and the constructor of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_class(){
		$this->assertClassHasAttribute('rooms', Rooms::class);
		$this->assertClassHasAttribute('selectedTable', Rooms::class);
		
		$rooms = new Rooms();
		$this->assertCount(57, $rooms->rooms());
		$this->assertNotNull($rooms->activeTable());
	}
	
	/** Function name: test_setRegistrationUser
	 *
	 * This function is testing the setRegistrationUser function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	
	function test_getRoomId(){
		$this->assertEquals(52, Rooms::getRoomId('322'));
		$this->assertEquals(null, Rooms::getRoomId('hallo'));
		$this->assertEquals(52, Rooms::getRoomId(322));
		$this->assertEquals(null, Rooms::getRoomId(3224));
		$this->assertEquals(null, Rooms::getRoomId(null));
	}
	
	function test_getResidents(){
		$room = new Rooms();
		$res = $room->getResidents("322");
		$this->assertNotNull($res);
		$this->assertCount(2, $res);
		
		$res =$room->getResidents(322);
		$this->assertNotNull($res);
		$this->assertCount(2, $res);
		
		$res =$room->getResidents("3000");
		$this->assertNotNull($res);
		$this->assertCount(0, $res);
		
		$res =$room->getResidents(null);
		$this->assertNotNull($res);
		$this->assertCount(0, $res);
	}
	
}