<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\User;

/** Class name: UserModelTest
 *
 * This class is the PHPUnit test for the Layout\User model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class UserModelTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_class
	 *
	 * This function is testing the class itself and the constructor of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_class(){
		$this->assertClassHasAttribute('user', User::class);
		$this->assertClassHasAttribute('permissions', User::class);
		$this->assertClassHasAttribute('notifications', User::class);
		$this->assertClassHasAttribute('unreadNotificationCount', User::class);

		$user = new User();
		$this->assertInstanceOf(\App\Classes\Data\User::class, $user->user());
		$this->assertCount(0, $user->permissions());
		$this->assertEquals(0, $user->notificationCount());
		$this->assertEquals(0, $user->unreadNotificationCount());
		
		$user = new User(1);
		$this->assertInstanceOf(\App\Classes\Data\User::class, $user->user());
		$this->assertCount(15, $user->permissions());
		$this->assertEquals(103, $user->notificationCount());
		$this->assertEquals(3, $user->unreadNotificationCount());
		
		$user = new User(200);
		$this->assertNull($user->user());
		$this->assertCount(0, $user->permissions());
		$this->assertEquals(0, $user->notificationCount());
		$this->assertEquals(0, $user->unreadNotificationCount());
	}

	/** Function name: test_getRoomId
	 *
	 * This function is testing the getRoomId function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_users(){
		$user = new User();
		$this->assertCount(6, $user->users());
		foreach($user->users() as $us){
			$this->assertInstanceOf(\App\Classes\Data\User::class, $us);
		}
	}
	
	function test_notifications(){
		$user = new User(1);
		$this->assertCount(5, $user->notifications());
		$this->assertCount(0, $user->notifications(-11, 3));
		$this->assertCount(0, $user->notifications(-1, 1));
		$this->assertCount(0, $user->notifications(0, 0));
		$this->assertCount(1, $user->notifications(0, 1));
		$this->assertCount(5, $user->notifications(0, 5));
		$this->assertCount(10, $user->notifications(5, 10));
		$this->assertCount(4, $user->notifications(100, 10));
		$this->assertCount(0, $user->notifications(110, 5));
		$this->assertCount(0, $user->notifications(20, -4));
		
		$this->assertCount(0, $user->notifications(null, -4));
		$this->assertCount(0, $user->notifications(20, null));
		$this->assertCount(0, $user->notifications(null, null));
	}
	
}