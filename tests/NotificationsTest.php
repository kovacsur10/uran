<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Database;
use App\Classes\Notifications;
use App\Exceptions\DatabaseException;
use App\Exceptions\UserNotFoundException;

/** Class name: NotificationsTest
 *
 * This class is the PHPUnit test for the Notifications model.
 * This is a unit test.
 *
 * Missing tests for functions:
 *   notify
 *   notifyAdmin
 *   notifyAdminFromServer
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class NotificationsTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_getNotifications_success
	 *
	 * This function is testing the getNotifications function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_getNotifications_success(){
		try{
			$notifications = Notifications::getNotifications(41);
			$this->assertCount(3, $notifications);
		}catch(\Exception $ex){
			$this->fail("An exception was not expected: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_getNotifications_fail
	 *
	 * This function is testing the getNotifications function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_getNotifications_fail(){
		try{
			Notifications::getNotifications(-1);
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_get_success
	 *
	 * This function is testing the get function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_get_success(){
		$this->assertNotNull(Notifications::get(237, 41), "The test notification should exist!");
	}
	
	/** Function name: test_get_fail
	 *
	 * This function is testing the get function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_get_fail(){
		$this->assertNull(Notifications::get(237, 0), "The test notification should not exist!");
		$this->assertNull(Notifications::get(0, 1), "The test notification should not exist!");
	}
	
	/** Function name: test_getUnreadNotificationCount_success
	 *
	 * This function is testing the getUnreadNotificationCount function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_getUnreadNotificationCount_success(){
		try{
			$count = Notifications::getUnreadNotificationCount(41);
			$this->assertEquals(2, $count);
		}catch(\Exception $ex){
			$this->fail("Exception was not expected: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_getUnreadNotificationCount_fail
	 *
	 * This function is testing the getUnreadNotificationCount function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_getUnreadNotificationCount_fail(){
		try{
			Notifications::getUnreadNotificationCount(-1);
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_setRead
	 *
	 * This function is testing the setRead function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_setRead(){
		$notification = Notifications::get(237, 41);
		$this->assertNotNull($notification, "The test notification should exist!");
		$this->assertFalse($notification->seen, "The #237 test notification should be unseen!");
		try{
			Notifications::setRead(237);
		}catch(\Exception $ex){
			$this->fail("setRead exception: ".$ex->getMessage());
		}
		$notification = Notifications::get(237, 41);
		$this->assertNotNull($notification, "The test notification should exist!");
		$this->assertTrue($notification->seen, "The #237 test notification should seen now!");
	}
	
}