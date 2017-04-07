<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\Rooms;
use App\Classes\Data\User;
use App\Exceptions\RoomNotFoundException;
use App\Exceptions\DatabaseException;
use App\Persistence\P_General;
use App\Exceptions\UserNotFoundException;
use App\Classes\Data\AssignmentTable;
use Illuminate\Support\Facades\Session;

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
		$this->assertEquals("2016-2017-1", $rooms->activeTable());
	}
	
	/** Function name: test_getRoomId
	 *
	 * This function is testing the getRoomId function of the Rooms model.
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
	
	/** Function name: test_getResidents
	 *
	 * This function is testing the getResidents function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getResidents(){
		$room = new Rooms();
		$res = $room->getResidents("322");
		$this->assertNotNull($res);
		$this->assertCount(1, $res);
		foreach($res as $resident){
			$this->assertInstanceOf(User::class, $resident);
		}
		
		$res =$room->getResidents(322);
		$this->assertNotNull($res);
		$this->assertCount(1, $res);
		foreach($res as $resident){
			$this->assertInstanceOf(User::class, $resident);
		}
		
		$res =$room->getResidents("3000");
		$this->assertNotNull($res);
		$this->assertCount(0, $res);
		
		$res =$room->getResidents(null);
		$this->assertNotNull($res);
		$this->assertCount(0, $res);
	}
	
	/** Function name: test_getFreePlaceCount
	 *
	 * This function is testing the getFreePlaceCount function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getFreePlaceCount(){
		$room = new Rooms();
		$this->assertEquals(1, $room->getFreePlaceCount("322"));
		$this->assertEquals(1, $room->getFreePlaceCount(322));
		$this->assertEquals(0, $room->getFreePlaceCount("3000"));
		$this->assertEquals(0, $room->getFreePlaceCount(null));
	}
	
	/** Function name: test_getRoomResidentListText
	 *
	 * This function is testing the getRoomResidentListText function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getRoomResidentListText(){
		$room = new Rooms();
		$this->assertEquals("Kovács Máté<br>free<br>", $room->getRoomResidentListText("322", "free"));
		$this->assertEquals("Kovács Máté<br>free<br>", $room->getRoomResidentListText(322, "free"));
		$this->assertEquals("", $room->getRoomResidentListText("3000", "free"));
		$this->assertEquals("", $room->getRoomResidentListText(null, "free"));
	}
	
	/** Function name: test_getFreePlaces
	 *
	 * This function is testing the getFreePlaces function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getFreePlaces(){
		$room = new Rooms();
		$freePlaces = $room->getFreePlaces();
		$this->assertNotNull($freePlaces);
		$this->assertCount(56, $freePlaces);
	}
	
	/** Function name: test_userHasResidence
	 *
	 * This function is testing the userHasResidence function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_userHasResidence(){
		$room = new Rooms();
		$this->assertTrue($room->userHasResidence(1));
		$this->assertTrue($room->userHasResidence("1"));
		$this->assertFalse($room->userHasResidence("alma"));
		$this->assertFalse($room->userHasResidence(41));
		$this->assertFalse($room->userHasResidence(-1));
		$this->assertFalse($room->userHasResidence(null));
	}
	
	/** Function name: test_emptyRoom_success
	 *
	 * This function is testing the emptyRoom function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_emptyRoom_success(){
		$room = new Rooms();
		$this->assertEquals(1, $room->getFreePlaceCount("322"));
		try{
			P_General::beginTransaction();
			$room->emptyRoom(52);
			$this->assertEquals(2, $room->getFreePlaceCount("322"));
			P_General::rollback();
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		$this->assertEquals(1, $room->getFreePlaceCount("322"));
		try{
			P_General::beginTransaction();
			$room->emptyRoom("52");
			$this->assertEquals(2, $room->getFreePlaceCount("322"));
			P_General::rollback();
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_emptyRoom_fail
	 *
	 * This function is testing the emptyRoom function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_emptyRoom_fail(){
		$room = new Rooms();
		try{
			$room->emptyRoom("3000");
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_emptyRoom_null
	 *
	 * This function is testing the emptyRoom function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_emptyRoom_null(){
		$room = new Rooms();
		try{
			$room->emptyRoom(null);
			$this->fail("An exception was expected!");
		}catch(RoomNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_setUserToRoom_success
	 *
	 * This function is testing the setUserToRoom function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setUserToRoom_success(){
		$room = new Rooms();
		
		$this->assertFalse($room->userHasResidence(41));
		$this->assertEquals(1, $room->getFreePlaceCount("322"));
		try{
			P_General::beginTransaction();
			$room->setUserToRoom(52, 41);
			$this->assertTrue($room->userHasResidence(41));
			$this->assertEquals(0, $room->getFreePlaceCount("322"));
			P_General::rollback();
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		$this->assertFalse($room->userHasResidence(41));
		$this->assertEquals(1, $room->getFreePlaceCount("322"));
		try{
			P_General::beginTransaction();
			$room->setUserToRoom("52", "41");
			$this->assertTrue($room->userHasResidence(41));
			$this->assertEquals(0, $room->getFreePlaceCount("322"));
			P_General::rollback();
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_setUserToRoom_fail
	 *
	 * This function is testing the setUserToRoom function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setUserToRoom_fail(){
		$room = new Rooms();
		
		$this->assertFalse($room->userHasResidence(41));
		$this->assertEquals(0, $room->getFreePlaceCount("227"));
		try{
			$room->setUserToRoom(25, 41);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
			$this->assertEquals("The room is full!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		$this->assertTrue($room->userHasResidence(34));
		$this->assertEquals(3, $room->getFreePlaceCount("226"));
		try{
			$room->setUserToRoom(24, 34);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
			$this->assertEquals("The user lives elsewhere!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			$room->setUserToRoom(300, 34);
			$this->fail("An exception was expected!");
		}catch(RoomNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			$room->setUserToRoom(25, -1);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_setUserToRoom_null
	 *
	 * This function is testing the setUserToRoom function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setUserToRoom_null(){
		$room = new Rooms();
		try{
			$room->setUserToRoom(null, 1);
			$this->fail("An exception was expected!");
		}catch(RoomNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$room->setUserToRoom(52, null);
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$room->setUserToRoom(null, null);
			$this->fail("An exception was expected!");
		}catch(RoomNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_getGuard
	 *
	 * This function is testing the getGuard function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getGuard(){
		$guard = Rooms::getGuard();
		$this->assertNotNull($guard);
		$this->assertEquals(1474817546, $guard);
	}
	
	/** Function name: test_checkGuard
	 *
	 * This function is testing the checkGuard function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_checkGuard(){
		$this->assertTrue(Rooms::checkGuard(1474817546));
		$this->assertTrue(Rooms::checkGuard("1474817546"));
		$this->assertFalse(Rooms::checkGuard(1474817545));
		$this->assertFalse(Rooms::checkGuard(null));
		$this->assertFalse(Rooms::checkGuard("alma"));
	}
	
	/** Function name: test_getTables
	 *
	 * This function is testing the getTables function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getTables(){
		$tables = Rooms::getTables();
		$this->assertNotNull($tables);
		$this->assertCount(3, $tables);
		foreach($tables as $table){
			$this->assertInstanceOf(AssignmentTable::class, $table);
		}
	}
	
	/** Function name: test_getTablesEX
	 *
	 * This function is testing the getTablesEX function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getTablesEX(){
		$rooms = new Rooms();
		$tables = $rooms->getTablesEX();
		$this->assertNotNull($tables);
		$this->assertCount(2, $tables);
		foreach($tables as $table){
			$this->assertInstanceOf(AssignmentTable::class, $table);
			$this->assertNotEquals($rooms->activeTable(), $table->name());
		}
	}
	
	/** Function name: test_selectTable_success
	 *
	 * This function is testing the selectTable function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_selectTable_success(){
		$rooms = new Rooms();
		
		$this->assertEquals("2016-2017-1", $rooms->activeTable());
		try{
			$rooms->selectTable("2016-2017-1");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$rooms = new Rooms();
		$this->assertEquals("2016-2017-1", $rooms->activeTable());
		
		$rooms = new Rooms();
		$this->assertEquals("2016-2017-1", $rooms->activeTable());
		try{
			$rooms->selectTable("2016-2017-2");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$rooms = new Rooms();
		$this->assertEquals("2016-2017-2", $rooms->activeTable());
	}
	
	/** Function name: test_selectTable_fail
	 *
	 * This function is testing the selectTable function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_selectTable_fail(){
		$rooms = new Rooms();
		try{
			$rooms->selectTable("alma");
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
			$this->assertEquals("Table not found!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$rooms->selectTable(1235);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
			$this->assertEquals("Table not found!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
		
	/** Function name: test_selectTable_null
	 *
	 * This function is testing the selectTable function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_selectTable_null(){
		$rooms = new Rooms();
		try{
			$rooms->selectTable(null);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
			$this->assertEquals("Table not found!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_addNewTable_success
	 *
	 * This function is testing the addNewTable function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_addNewTable_success(){
		$rooms = new Rooms();
		$this->assertCount(3, Rooms::getTables());
		try{
			$rooms->addNewTable("2017-2018-new");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertCount(4, Rooms::getTables());
	}
	
	/** Function name: test_addNewTable_fail
	 *
	 * This function is testing the addNewTable function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_addNewTable_fail(){
		$rooms = new Rooms();
		try{
			$rooms->addNewTable("2016-2017-1");
		}catch(DatabaseException $ex){
			$this->assertEquals("Table already exists!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$rooms->addNewTable("");
		}catch(DatabaseException $ex){
			$this->assertEquals("Table already exists!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_addNewTable_null
	 *
	 * This function is testing the addNewTable function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_addNewTable_null(){
		$rooms = new Rooms();
		try{
			$rooms->addNewTable(null);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
			$this->assertEquals("Table already exists!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_removeTable_success
	 *
	 * This function is testing the removeTable function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_removeTable_success(){
		$rooms = new Rooms();
		$this->assertCount(3, Rooms::getTables());
		try{
			$rooms->removeTable("2016-2017-2");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertCount(2, Rooms::getTables());
	}
	
	/** Function name: test_removeTable_fail
	 *
	 * This function is testing the removeTable function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_removeTable_fail(){
		$rooms = new Rooms();
		try{
			$rooms->removeTable("2016-2017-1");
		}catch(DatabaseException $ex){
			$this->assertEquals("Table not exist or it is the active one!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	
		try{
			$rooms->removeTable("");
		}catch(DatabaseException $ex){
			$this->assertEquals("Table not exist or it is the active one!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$rooms->removeTable("2016-2017-222145");
		}catch(DatabaseException $ex){
			$this->assertEquals("Table not exist or it is the active one!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_removeTable_null
	 *
	 * This function is testing the removeTable function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_removeTable_null(){
		$rooms = new Rooms();
		try{
			$rooms->removeTable(null);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
			$this->assertEquals("Table not exist or it is the active one!", $ex->getMessage());
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_checkLevel
	 *
	 * This function is testing the checkLevel function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_checkLevel(){
		$this->assertFalse(Session::has('rooms_show_level'));
		$this->assertEquals(2, Rooms::checkLevel(null));
		$this->assertTrue(Session::has('rooms_show_level'));
		$this->assertEquals(2, Session::get('rooms_show_level'));
		
		Session::flush();
		Session::put('rooms_show_level', 0);
		
		$this->assertTrue(Session::has('rooms_show_level'));
		$this->assertEquals(0, Session::get('rooms_show_level'));
		$this->assertEquals(0, Rooms::checkLevel(null));
		$this->assertTrue(Session::has('rooms_show_level'));
		$this->assertEquals(0, Session::get('rooms_show_level'));
		
		Session::flush();
		Session::put('rooms_show_level', 6);
		
		$this->assertTrue(Session::has('rooms_show_level'));
		$this->assertEquals(6, Session::get('rooms_show_level'));
		$this->assertEquals(2, Rooms::checkLevel(null));
		$this->assertTrue(Session::has('rooms_show_level'));
		$this->assertEquals(2, Session::get('rooms_show_level'));
		
		Session::flush();
		
		$this->assertFalse(Session::has('rooms_show_level'));
		$this->assertEquals(2, Rooms::checkLevel(6));
		$this->assertTrue(Session::has('rooms_show_level'));
		$this->assertEquals(2, Session::get('rooms_show_level'));
		
		Session::flush();
		
		$this->assertFalse(Session::has('rooms_show_level'));
		$this->assertEquals(1, Rooms::checkLevel(1));
		$this->assertTrue(Session::has('rooms_show_level'));
		$this->assertEquals(1, Session::get('rooms_show_level'));
		
		Session::flush();
		Session::put('rooms_show_level', 1);
		
		$this->assertTrue(Session::has('rooms_show_level'));
		$this->assertEquals(1, Session::get('rooms_show_level'));
		$this->assertEquals(-2, Rooms::checkLevel(-2));
		$this->assertTrue(Session::has('rooms_show_level'));
		$this->assertEquals(-2, Session::get('rooms_show_level'));
	}
	
	/** Function name: test_getSessionData
	 *
	 * This function is testing the getSessionData function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getSessionData(){
		$this->assertFalse(Session::has('rooms_show_level'));
		$this->assertCount(0, Rooms::getSessionData());
		$this->assertFalse(Session::has('rooms_show_level'));
		
		Session::flush();
		Session::put('rooms_show_level', 3);
		
		$this->assertTrue(Session::has('rooms_show_level'));
		$this->assertEquals(["rooms_show_level" => 3], Rooms::getSessionData());
		$this->assertTrue(Session::has('rooms_show_level'));
		
		Session::flush();
		Session::put('no_key_like_this', 3);
		
		$this->assertTrue(Session::has('no_key_like_this'));
		$this->assertFalse(Session::has('rooms_show_level'));
		$this->assertCount(0, Rooms::getSessionData());
		$this->assertTrue(Session::has('no_key_like_this'));
		$this->assertFalse(Session::has('rooms_show_level'));
	}
}