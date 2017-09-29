<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\Registrations;
use App\Classes\Data\User;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;
use App\Persistence\P_User;
use App\Classes\Layout\Permissions;

/** Class name: RegistrationsTest
 *
 * This class is the PHPUnit test for the Layout\Registrations model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class RegistrationsTest extends BrowserKitTestCase
{
	use DatabaseTransactions;

	/** Function name: test_class
	 *
	 * This function is testing the class itself and the constructor of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_class(){
		$this->assertClassHasAttribute('registrationUser', Registrations::class);
		
		$registration = new Registrations();
		$this->assertNull($registration->getRegistrationUser());
	}
	
	/** Function name: test_setRegistrationUser
	 *
	 * This function is testing the setRegistrationUser function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setRegistrationUser(){
		$registration = new Registrations();
		$this->assertNull($registration->getRegistrationUser());
		$registration->setRegistrationUser(1);
		$this->assertNull($registration->getRegistrationUser());
		$registration->setRegistrationUser(18);
		$user = $registration->getRegistrationUser();
		$this->assertInstanceOf(User::class, $user);
		$this->assertFalse($user->registered());
		$this->assertEquals(18, $user->id());
	}
	
	/** Function name: test_get
	 *
	 * This function is testing the get function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_get(){
		$registrationUsers = Registrations::get();
		$this->assertCount(1, $registrationUsers);
	}
	
	/** Function name: test_verify_success
	 *
	 * This function is testing the verify function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_verify_success(){
		$reg = new Registrations();
		$reg->setRegistrationUser(18);
		$this->assertFalse($reg->getRegistrationUser()->verified());
		try{
			Registrations::verify("c04c6694fadd38f33937e80a8dce1057b7c497a9");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$reg->setRegistrationUser(18);
		$this->assertTrue($reg->getRegistrationUser()->verified());
	}
	
	/** Function name: test_verify_fail
	 *
	 * This function is testing the verify function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_verify_fail(){
		$user = \App\Classes\Layout\User::getUserData(41);
		$this->assertTrue($user->verified());
		$this->assertEquals("2016-09-06 22:09:53", $user->verificationDate());
		try{
			Registrations::verify("fb7d80e0242a10ab5ce76d707408a044213a23f7");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$user = \App\Classes\Layout\User::getUserData(41);
		$this->assertTrue($user->verified());
		$this->assertEquals("2016-09-06 22:09:53", $user->verificationDate());
		
		try{
			Registrations::verify("almafa");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_verify_null
	 *
	 * This function is testing the verify function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_verify_null(){
		try{
			Registrations::verify(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_reject_success
	 *
	 * This function is testing the reject function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_reject_success(){
		$reg = new Registrations();
		$reg->setRegistrationUser(18);
		$this->assertNotNull($reg->getRegistrationUser());
		try{
			\App\Classes\Layout\User::getUserData(18);
			$this->fail("Should throw exceptions!");
		}catch(\Exception $ex){
		}
		try{
			Registrations::reject(18);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$reg->setRegistrationUser(18);
		$this->assertNull($reg->getRegistrationUser());
		try{
			\App\Classes\Layout\User::getUserData(18);
			$this->fail("Should throw exceptions!");
		}catch(\Exception $ex){
		}
	}
	
	/** Function name: test_reject_fail
	 *
	 * This function is testing the reject function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_reject_fail(){
		$reg = new Registrations();
		$reg->setRegistrationUser(41);
		$this->assertNull($reg->getRegistrationUser());
		$this->assertNotNull(\App\Classes\Layout\User::getUserData(41));
		try{
			Registrations::reject(41);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$reg = new Registrations();
		$reg->setRegistrationUser(41);
		$this->assertNotNull(\App\Classes\Layout\User::getUserData(41));
		
		try{
			Registrations::reject(2);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::reject("alma");
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_reject_null
	 *
	 * This function is testing the reject function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_reject_null(){
		try{
			Registrations::reject(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_acceptGuest_success
	 *
	 * This function is testing the acceptGuest function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_acceptGuest_success(){
		$reg = new Registrations();
		$reg->setRegistrationUser(18);
		$this->assertNotNull($reg->getRegistrationUser());
		try{
			\App\Classes\Layout\User::getUserData(18);
			$this->fail("Should throw exceptions!");
		}catch(\Exception $ex){
		}
		try{
			Registrations::acceptGuest(18, "HUN", "Veszprém", "8200", "This is my address.", "Veszprém", "+36307501832", "This is a fake reason.");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$reg->setRegistrationUser(18);
		$this->assertNull($reg->getRegistrationUser());
		$this->assertNotNull(\App\Classes\Layout\User::getUserData(18));
	}
	
	/** Function name: test_acceptGuest_fail
	 *
	 * This function is testing the acceptGuest function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_acceptGuest_fail(){
		$reg = new Registrations();
		$reg->setRegistrationUser(41);
		$this->assertNull($reg->getRegistrationUser());
		$this->assertNotNull(\App\Classes\Layout\User::getUserData(41));
		try{
			Registrations::acceptGuest(41, "HUN", "Veszprém", "8200", "This is my address.", "Veszprém", "+36307501832", "This is a fake reason.");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$reg->setRegistrationUser(41);
		$this->assertNull($reg->getRegistrationUser());
		$this->assertNotNull(\App\Classes\Layout\User::getUserData(41));
	}
	
	/** Function name: test_acceptGuest_null
	 *
	 * This function is testing the acceptGuest function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_acceptGuest_null(){
		try{
			Registrations::acceptGuest(null, "HUN", "Veszprém", "8200", "This is my address.", "Veszprém", "+36307501832", "This is a fake reason.");
			$this->fail("Should throw an exception!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::acceptGuest(18, null, null, null, null, null, null, null);
			$this->fail("Should throw an exception!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail(" Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_acceptCollegist_success
	 *
	 * This function is testing the acceptCollegist function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_acceptCollegist_success(){
		$reg = new Registrations();
		$reg->setRegistrationUser(18);
		$this->assertNotNull($reg->getRegistrationUser());
		try{
			\App\Classes\Layout\User::getUserData(18);
			$this->fail("Should throw exceptions!");
		}catch(\Exception $ex){
		}
		try{
			Registrations::acceptCollegist(18, "HUN", "Veszprém", "8200", "Sigray utca 18.", "Veszprém", "+36307501832", "Budapest", "1993-05-22", "mother name", 2004, "high school", "almahq", 2008, [1,2], [1]);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$reg->setRegistrationUser(18);
		$this->assertNull($reg->getRegistrationUser());
		$this->assertNotNull(\App\Classes\Layout\User::getUserData(18));
	}
	
	/** Function name: test_acceptCollegist_fail
	 *
	 * This function is testing the acceptCollegist function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_acceptCollegist_fail(){
		$reg = new Registrations();
		$reg->setRegistrationUser(41);
		$this->assertNull($reg->getRegistrationUser());
		$this->assertNotNull(\App\Classes\Layout\User::getUserData(41));
		try{
			Registrations::acceptCollegist(41, "HUN", "Veszprém", "8200", "Sigray utca 18.", "Veszprém", "+36307501832", "Budapest", "1993-05-22", "mother name", 2004, "high school", "almahq", 2008, [1], [1]);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$reg->setRegistrationUser(41);
		$this->assertNull($reg->getRegistrationUser());
		$this->assertNotNull(\App\Classes\Layout\User::getUserData(41));
	}
	
	/** Function name: test_acceptCollegist_null
	 *
	 * This function is testing the acceptCollegist function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_acceptCollegist_null(){
		try{
			Registrations::acceptCollegist(null, "HUN", "Veszprém", "8200", "Sigray utca 18.", "Veszprém", "+36307501832", "Budapest", "1993-05-22", "mother name", 2004, "high school", "almahq", 2008, [], []);
			$this->fail("Should throw an exception!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::acceptCollegist(18, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
			$this->fail("Should throw an exception!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_register_success_collegist
	 *
	 * This function is testing the register function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_register_success_collegist(){
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", null, "+36307501832", "HUN", "Veszprém", "1994-05-27 12:00:00", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1,2], [1,6]);
			$userId = P_User::getRegistrationUserIdForUsername("testRegistrationFromUnitTest");
			$groups = Permissions::getGroupsForUser($userId);
			$this->assertCount(1, $groups);
			$this->assertEquals(2, $groups[0]->id());
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_register_success_guest
	 *
	 * This function is testing the register function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_register_success_guest(){
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", 'this is a reason', "+36307501832", "HUN", null, null, null, null, null, null, null, null, null);
			$userId = P_User::getRegistrationUserIdForUsername("testRegistrationFromUnitTest");
			$groups = Permissions::getGroupsForUser($userId);
			$this->assertCount(1, $groups);
			$this->assertEquals(1, $groups[0]->id());
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_register_fail
	 *
	 * This function is testing the register function of the Registrations model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_register_fail(){
		try{
			Registrations::register(null, "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", "Ez egy ok!", "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], []);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", null, "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", "Ez egy ok!", "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", null, "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", "Ez egy ok!", "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1, 7]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", null, "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", "Ez egy ok!", "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", null, "Veszprém", "8200", "Sigray utca 18", "Veszprém", "Ez egy ok!", "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", null, "8200", "Sigray utca 18", "Veszprém", "Ez egy ok!", "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", null, "Sigray utca 18", "Veszprém", "Ez egy ok!", "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", null, "Veszprém", "Ez egy ok!", "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", null, "Ez egy ok!", "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", "Ez egy ok!", null, "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", "Ez egy ok!", "+36307501832", null, "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", null, "+36307501832", "HUN", null, "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", null, "+36307501832", "HUN", "Veszprém", null, "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", null, "+36307501832", "HUN", "Veszprém", "1994-05-27", null, 2008, "Unit test school", "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", null, "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", null, "Unit test school", "AAAAAA", 2011, [], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", null, "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, null, "AAAAAA", 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", null, "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", null, 2011, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", null, "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", null, [1], [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", null, "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, null, [1]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Registrations::register("testRegistrationFromUnitTest", "test", "kovacsur10@freemail.hu", "Urán unit teszt", "HUN", "Veszprém", "8200", "Sigray utca 18", "Veszprém", null, "+36307501832", "HUN", "Veszprém", "1994-05-27", "anyja neve", 2008, "Unit test school", "AAAAAA", 2011, [1], null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
}