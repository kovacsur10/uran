<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\User;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\ValueMismatchException;

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
		$this->assertCount(16, $user->permissions());
		$this->assertEquals(103, $user->notificationCount());
		$this->assertEquals(3, $user->unreadNotificationCount());
		
		$user = new User(200);
		$this->assertNull($user->user());
		$this->assertCount(0, $user->permissions());
		$this->assertEquals(0, $user->notificationCount());
		$this->assertEquals(0, $user->unreadNotificationCount());
	}

	/** Function name: test_users
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
	
	/** Function name: test_isIntern
	 *
	 * This function is testing the isIntern function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_isIntern(){
		$user = new User(25);
		$this->assertTrue($user->isIntern());
		
		$user = new User(0);
		$this->assertFalse($user->isIntern());
	}
	
	/** Function name: test_isLivingIn
	 *
	 * This function is testing the isLivingIn function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_isLivingIn(){
		$user = new User(25);
		$this->assertTrue($user->isLivingIn());
		
		$user = new User(0);
		$this->assertFalse($user->isLivingIn());
	}
	
	/** Function name: test_isCollegist
	 *
	 * This function is testing the isCollegist function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_isCollegist(){
		$user = new User(1);
		$this->assertTrue($user->isCollegist());
		
		$user = new User(0);
		$this->assertFalse($user->isCollegist());
	}
	
	/** Function name: test_notifications
	 *
	 * This function is testing the notifications function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_notifications(){
		$user = new User(1);
		$this->assertCount(5, $user->notifications());
		$this->assertCount(0, $user->notifications(-11, 3));
		$this->assertCount(0, $user->notifications(-1, 1));
		$this->assertCount(0, $user->notifications(0, 0));
		$this->assertCount(1, $user->notifications(0, 1));
		$this->assertCount(5, $user->notifications(0, 5));
		$this->assertCount(10, $user->notifications(5, 10));
		$this->assertCount(3, $user->notifications(100, 10));
		$this->assertCount(0, $user->notifications(110, 5));
		$this->assertCount(0, $user->notifications(20, -4));
		
		$this->assertCount(0, $user->notifications(null, -4));
		$this->assertCount(0, $user->notifications(20, null));
		$this->assertCount(0, $user->notifications(null, null));
	}
	
	/** Function name: test_permitted
	 *
	 * This function is testing the permitted function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_permitted(){
		$user = new User();
		$this->assertFalse($user->permitted(null));
		$this->assertFalse($user->permitted(''));
		$this->assertFalse($user->permitted('alma'));
		$this->assertFalse($user->permitted('tasks_admin'));
		$this->assertFalse($user->permitted('rooms_observe_assignment'));
		
		$user = new User(1);
		$this->assertFalse($user->permitted(null));
		$this->assertFalse($user->permitted(''));
		$this->assertFalse($user->permitted('alma'));
		$this->assertTrue($user->permitted('tasks_admin'));
		$this->assertTrue($user->permitted('rooms_observe_assignment'));
		
		$user = new User(41);
		$this->assertFalse($user->permitted(null));
		$this->assertFalse($user->permitted(''));
		$this->assertFalse($user->permitted('alma'));
		$this->assertFalse($user->permitted('tasks_admin'));
		$this->assertTrue($user->permitted('rooms_observe_assignment'));
	}
	
	/** Function name: test_getUserData
	 *
	 * This function is testing the getUserData function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getUserData(){
		//exception cases
		try{
			$userData = User::getUserData(null);
			$this->fail("Expected an exception!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$userData = User::getUserData(100);
			$this->fail("Expected an exception!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$userData = User::getUserData(-2);
			$this->fail("Expected an exception!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		//good cases
		try{
			$userData = User::getUserData(0);
			$this->assertNotNull($userData);
			$this->assertInstanceOf(App\Classes\Data\User::class, $userData);
			$this->assertEquals('admin', $userData->username());
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		
		try{
			$userData = User::getUserData(1);
			$this->assertNotNull($userData);
			$this->assertInstanceOf(App\Classes\Data\User::class, $userData);
			$this->assertEquals('kovacsur10', $userData->username());
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_getUserDataByUsername
	 *
	 * This function is testing the getUserDataByUsername function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getUserDataByUsername(){
		//exception cases
		try{
			$userData = User::getUserDataByUsername(null);
			$this->fail("Expected an exception!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$userData = User::getUserDataByUsername('');
			$this->fail("Expected an exception!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$userData = User::getUserDataByUsername('no_such_user');
			$this->fail("Expected an exception!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		//good cases
		try{
			$userData = User::getUserDataByUsername('admin');
			$this->assertNotNull($userData);
			$this->assertInstanceOf(App\Classes\Data\User::class, $userData);
			$this->assertEquals(0, $userData->id());
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		
		try{
			$userData = User::getUserDataByUsername('kovacsur10');
			$this->assertNotNull($userData);
			$this->assertInstanceOf(App\Classes\Data\User::class, $userData);
			$this->assertEquals(1, $userData->id());
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_saveUserLanguage
	 *
	 * This function is testing the saveUserLanguage function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_saveUserLanguage(){
		//fail cases
		$user = new User(1);
		$this->assertEquals('hu', $user->user()->language());
		try{
			$user->saveUserLanguage('en_GB');
			$this->fail("Expected an exception!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$user = new User(1);
		$this->assertEquals('hu', $user->user()->language());
		
		$user = new User(1);
		$this->assertEquals('hu', $user->user()->language());
		try{
			$user->saveUserLanguage(null);
			$this->fail("Expected an exception!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$user = new User(1);
		$this->assertEquals('hu', $user->user()->language());
		
		//success cases
		$user = new User(0);
		$this->assertEquals('hu', $user->user()->language());
		try{
			$user->saveUserLanguage('hu');
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		$user = new User(1);
		$this->assertEquals('hu', $user->user()->language());
		
		$user = new User(1);
		$this->assertEquals('hu', $user->user()->language());
		try{
			$user->saveUserLanguage('hu');
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		$user = new User(1);
		$this->assertEquals('hu', $user->user()->language());
		
		$user = new User(1);
		$this->assertEquals('hu', $user->user()->language());
		try{
			$user->saveUserLanguage('en');
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		$user = new User(1);
		$this->assertEquals('en', $user->user()->language());
	}
	
	/** Function name: test_getForMembraMailingList
	 *
	 * This function is testing the getForMembraMailingList function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getForMembraMailingList(){
		$users = User::getForMembraMailingList(null);
		$this->assertCount(4, $users["alreadyMember"]);
		$this->assertCount(0, $users["new"]);
		$this->assertCount(0, $users["remove"]);
		
		$users = User::getForMembraMailingList(["almafa"]);
		$this->assertCount(4, $users["alreadyMember"]);
		$this->assertCount(0, $users["new"]);
		$this->assertCount(0, $users["remove"]);
		
		$users = User::getForMembraMailingList("ALMAFA");
		$this->assertCount(4, $users["alreadyMember"]);
		$this->assertCount(0, $users["new"]);
		$this->assertCount(0, $users["remove"]);
		
		$users = User::getForMembraMailingList("HAL <hal@lo.hu>\nNagy Vendel <vendi95@gmail.com>");
		$this->assertCount(1, $users["alreadyMember"]);
		$this->assertCount(3, $users["new"]);
		$this->assertCount(1, $users["remove"]);
	}
	
	/** Function name: test_getForAlumniMailingList
	 *
	 * This function is testing the getForAlumniMailingList function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getForAlumniMailingList(){
		$users = User::getForAlumniMailingList(null);
		$this->assertCount(3, $users["alreadyMember"]);
		$this->assertCount(0, $users["new"]);
		$this->assertCount(0, $users["remove"]);
		
		$users = User::getForAlumniMailingList(["almafa"]);
		$this->assertCount(3, $users["alreadyMember"]);
		$this->assertCount(0, $users["new"]);
		$this->assertCount(0, $users["remove"]);
		
		$users = User::getForAlumniMailingList("ALMAFA");
		$this->assertCount(3, $users["alreadyMember"]);
		$this->assertCount(0, $users["new"]);
		$this->assertCount(0, $users["remove"]);
		
		$users = User::getForAlumniMailingList("HAL <hal@lo.hu>\nExtra Irén 1 <extra.iran@alma.hu>\nRévfalusi Éva <eva.revfalusi@gmail.com>");
		$this->assertCount(2, $users["alreadyMember"]);
		$this->assertCount(1, $users["new"]);
		$this->assertCount(1, $users["remove"]);
	}
	
	/** Function name: test_getForRgMailingList
	 *
	 * This function is testing the getForRgMailingList function of the User model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getForRgMailingList(){
		$users = User::getForRgMailingList(null);
		$this->assertCount(2, $users["alreadyMember"]);
		$this->assertCount(0, $users["new"]);
		$this->assertCount(0, $users["remove"]);
		
		$users = User::getForRgMailingList(["almafa"]);
		$this->assertCount(2, $users["alreadyMember"]);
		$this->assertCount(0, $users["new"]);
		$this->assertCount(0, $users["remove"]);
		
		$users = User::getForRgMailingList("ALMAFA");
		$this->assertCount(2, $users["alreadyMember"]);
		$this->assertCount(0, $users["new"]);
		$this->assertCount(0, $users["remove"]);
		
		$users = User::getForRgMailingList("Nagy Vendel <vendi95@gmail.com>\nHAL <hal@lo.hu>");
		$this->assertCount(1, $users["alreadyMember"]);
		$this->assertCount(1, $users["new"]);
		$this->assertCount(1, $users["remove"]);
	}
	
}