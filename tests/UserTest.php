<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\User;
use App\Classes\Data\StatusCode;

/** Class name: UserTest
 *
 * This class is the PHPUnit test for the Data\User data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class UserTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_permission
	 *
	 * This function is testing the User data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_permission(){
		$user = new User(1, "user", "username", "pazzword", "e@mail", "2016 date", new StatusCode(1, "stat"), "2016-11-11", "hu_HU", true, true, "2010-12-11 23:11:23", "thisistheregcode", "Hungary", "Veszprém1", "Veszprém2", "Budapest út 11-13.", "8200");
		$this->assertEquals(1, $user->id());
		$this->assertEquals("user", $user->name());
		$this->assertEquals("username", $user->username());
		$this->assertEquals("pazzword", $user->password());
		$this->assertEquals("e@mail", $user->email());
		$this->assertEquals("2016 date", $user->registrationDate());
		$this->assertInstanceOf(StatusCode::class, $user->status());
		$this->assertEquals("2016-11-11", $user->lastOnline());
		$this->assertEquals("hu_HU", $user->language());
		$this->assertTrue($user->registered());
		$this->assertTrue($user->verified());
		$this->assertEquals("2010-12-11 23:11:23", $user->verificationDate());
		$this->assertEquals("thisistheregcode", $user->registrationCode());
		$this->assertEquals("Hungary", $user->country());
		$this->assertEquals("Veszprém1", $user->city());
		$this->assertEquals("Veszprém2", $user->shire());
		$this->assertEquals("Budapest út 11-13.", $user->address());
		$this->assertEquals("8200", $user->postalCode());
	}

	/** Function name: test_permission_attr
	 *
	 * This function is testing the User data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_permission_attr(){
		$this->assertClassHasAttribute('id', User::class);
		$this->assertClassHasAttribute('name', User::class);
		$this->assertClassHasAttribute('username', User::class);
		$this->assertClassHasAttribute('password', User::class);
		$this->assertClassHasAttribute('email', User::class);
		$this->assertClassHasAttribute('registration_date', User::class);
		$this->assertClassHasAttribute('status', User::class);
		$this->assertClassHasAttribute('last_online', User::class);
		$this->assertClassHasAttribute('language', User::class);
		$this->assertClassHasAttribute('registered', User::class);
		$this->assertClassHasAttribute('registration_verification_date', User::class);
		$this->assertClassHasAttribute('registration_code', User::class);
		$this->assertClassHasAttribute('registration_verification_status', User::class);
		$this->assertClassHasAttribute('city', User::class);
		$this->assertClassHasAttribute('country', User::class);
		$this->assertClassHasAttribute('shire', User::class);
		$this->assertClassHasAttribute('address', User::class);
		$this->assertClassHasAttribute('postal_code', User::class);
	}
}