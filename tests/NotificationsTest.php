<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Notifications;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\ValueMismatchException;
use App\Classes\Layout\User;
use App\Classes\Data\Notification;

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
class NotificationsTest extends BrowserKitTestCase
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
			foreach($notifications as $notification){
				$this->assertInstanceOf(Notification::class, $notification);
			}
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
	
	/** Function name: test_getNotifications_null
	 *
	 * This function is testing the getNotifications function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_getNotifications_null(){
		try{
			Notifications::getNotifications(null);
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
		$notification = Notifications::get(237, 41);
		$this->assertNotNull($notification, "The test notification should exist!");
		$this->assertInstanceOf(Notification::class, $notification);
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
		$this->assertNull(Notifications::get(null, 41), "The test notification should not exist!");
		$this->assertNull(Notifications::get(237, null), "The test notification should not exist!");
		$this->assertNull(Notifications::get(null, null), "The test notification should not exist!");
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
	
	/** Function name: test_getUnreadNotificationCount_null
	 *
	 * This function is testing the getUnreadNotificationCount function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_getUnreadNotificationCount_null(){
		try{
			Notifications::getUnreadNotificationCount(null);
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
		$this->assertFalse($notification->isSeen(), "The #237 test notification should be unseen!");
		try{
			Notifications::setRead(237);
		}catch(\Exception $ex){
			$this->fail("setRead exception: ".$ex->getMessage());
		}
		$notification = Notifications::get(237, 41);
		$this->assertNotNull($notification, "The test notification should exist!");
		$this->assertTrue($notification->isSeen(), "The #237 test notification should seen now!");
	}
	
	/** Function name: test_setRead_notreal
	 *
	 * This function is testing the setRead function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_setRead_notreal(){
		try{
			Notifications::setRead(0);	
		}catch(\Exception $ex){
			$this->fail("Unexpected exception! ".$ex->getMessage());
		}
	}
	
	/** Function name: test_setRead_null
	 *
	 * This function is testing the setRead function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_setRead_null(){
		try{
			Notifications::setRead(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Unexpected exception! ".$ex->getMessage());
		}
	}
	
	/** Function name: test_notify_success
	 *
	 * This function is testing the notify function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_notify_success(){
		$this->assertEquals(2, Notifications::getUnreadNotificationCount(41));
		Notifications::notify(User::getUserData(1), 41, "test", "message", "route");
		$this->assertEquals(3, Notifications::getUnreadNotificationCount(41));
	}
	
	/** Function name: test_notify_null
	 *
	 * This function is testing the notify function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_notify_null(){
		$user = User::getUserData(1);
		$this->assertEquals(2, Notifications::getUnreadNotificationCount(41));
		Notifications::notify($user, null, "test", "message", "route");
		$this->assertEquals(2, Notifications::getUnreadNotificationCount(41));
		Notifications::notify($user, 41, null, "message", "route");
		$this->assertEquals(2, Notifications::getUnreadNotificationCount(41));
		Notifications::notify($user, 41, "test", null, "route");
		$this->assertEquals(2, Notifications::getUnreadNotificationCount(41));
		
		Notifications::notify($user, 41, "test", "message", null);
		$this->assertEquals(3, Notifications::getUnreadNotificationCount(41));
	}
	
	/** Function name: test_notifyAdmin_success
	 *
	 * This function is testing the notifyAdmin function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_notifyAdmin_success(){
		$this->assertEquals(0, Notifications::getUnreadNotificationCount(34));
		Notifications::notifyAdmin(User::getUserData(41), "test_permission", "test", "message", "route");
		$this->assertEquals(1, Notifications::getUnreadNotificationCount(34));
	}
	
	/** Function name: test_notifyAdmin_null
	 *
	 * This function is testing the notifyAdmin function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_notifyAdmin_null(){
		$user = User::getUserData(1);
		$this->assertEquals(0, Notifications::getUnreadNotificationCount(34));
		Notifications::notifyAdmin($user, null, "test", "message", "route");
		$this->assertEquals(0, Notifications::getUnreadNotificationCount(34));
		Notifications::notifyAdmin($user, "test_permission", null, "message", "route");
		$this->assertEquals(0, Notifications::getUnreadNotificationCount(34));
		Notifications::notifyAdmin($user, "test_permission", "test", null, "route");
		$this->assertEquals(0, Notifications::getUnreadNotificationCount(34));
		
		Notifications::notifyAdmin($user, "test_permission", "test", "message", null);
		$this->assertEquals(1, Notifications::getUnreadNotificationCount(34));
	}
	
	/** Function name: test_notifyAdminFromServer_success
	 *
	 * This function is testing the notifyAdminFromServer function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_notifyAdminFromServer_success(){
		$this->assertEquals(0, Notifications::getUnreadNotificationCount(34));
		Notifications::notifyAdminFromServer("test_permission", "test", "message", "route");
		$this->assertEquals(1, Notifications::getUnreadNotificationCount(34));
	}
	
	/** Function name: test_notifyAdminFromServer_null
	 *
	 * This function is testing the notifyAdminFromServer function of the Notifications model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_notifyAdminFromServer_null(){
		$user = User::getUserData(1);
		$this->assertEquals(0, Notifications::getUnreadNotificationCount(34));
		Notifications::notifyAdminFromServer(null, "test", "message", "route");
		$this->assertEquals(0, Notifications::getUnreadNotificationCount(34));
		Notifications::notifyAdminFromServer("test_permission", null, "message", "route");
		$this->assertEquals(0, Notifications::getUnreadNotificationCount(34));
		Notifications::notifyAdminFromServer("test_permission", "test", null, "route");
		$this->assertEquals(0, Notifications::getUnreadNotificationCount(34));
		
		Notifications::notifyAdminFromServer("test_permission", "test", "message", null);
		$this->assertEquals(1, Notifications::getUnreadNotificationCount(34));
	}
	
}