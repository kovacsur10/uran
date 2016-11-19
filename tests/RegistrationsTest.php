<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\Registrations;
use App\Classes\Data\User;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;

/** Class name: RegistrationsTest
 *
 * This class is the PHPUnit test for the Layout\Registrations model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class RegistrationsTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_faculties
	 *
	 * This function is testing the faculties function of the Registrations model.
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
	
	function test_get(){
		$registrationUsers = Registrations::get();
		$this->assertCount(1, $registrationUsers);
	}
	
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
	
	function test_verify_null(){
		try{
			Registrations::verify(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
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
	
	function test_reject_null(){
		try{
			Registrations::reject(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
}