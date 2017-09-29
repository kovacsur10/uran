<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\Notification;

/** Class name: NotificationTest
 *
 * This class is the PHPUnit test for the Data\Notification data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class NotificationTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_notification
	 *
	 * This function is testing the Notification data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_notification(){
		$notification = new Notification(1, "alma", "Alma Béla", "first notification", "welcome!", "1994-05-27", false, true, "route!");
		$this->assertEquals(1, $notification->id());
		$this->assertEquals("alma", $notification->name());
		$this->assertEquals("Alma Béla", $notification->username());
		$this->assertEquals("welcome!", $notification->message());
		$this->assertEquals("first notification", $notification->subject());
		$this->assertEquals("1994-05-27", $notification->time());
		$this->assertFalse($notification->isSeen());
		$this->assertTrue($notification->isAdmin());
		$this->assertEquals("route!", $notification->route());

		$notification = new Notification("1", "alma", "Alma Béla", "first notification", "welcome!", "1994-05-27", true, false, null);
		$this->assertEquals(1, $notification->id());
		$this->assertEquals("alma", $notification->name());
		$this->assertEquals("Alma Béla", $notification->username());
		$this->assertEquals("welcome!", $notification->message());
		$this->assertEquals("first notification", $notification->subject());
		$this->assertEquals("1994-05-27", $notification->time());
		$this->assertTrue($notification->isSeen());
		$this->assertFalse($notification->isAdmin());
		$this->assertNull($notification->route());
	}

	/** Function name: test_notification_attr
	 *
	 * This function is testing the Notification data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_notification_attr(){
		$this->assertClassHasAttribute('id', Notification::class);
		$this->assertClassHasAttribute('name', Notification::class);
		$this->assertClassHasAttribute('username', Notification::class);
		$this->assertClassHasAttribute('seen', Notification::class);
		$this->assertClassHasAttribute('admin', Notification::class);
		$this->assertClassHasAttribute('subject', Notification::class);
		$this->assertClassHasAttribute('message', Notification::class);
		$this->assertClassHasAttribute('route', Notification::class);
		$this->assertClassHasAttribute('time', Notification::class);
		$this->assertTrue(true); //All attributes are okay... from PHPUnit 6, no assertion is reported as a risk
	}
}