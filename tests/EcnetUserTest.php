<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\EcnetUser;

/** Class name: EcnetUserTest
 *
 * This class is the PHPUnit test for the Data\EcnetUser data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class EcnetUserTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_permission
	 *
	 * This function is testing the EcnetUser data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_permission(){
		$user = new EcnetUser(1, "user", "username", '1994-05-27 05:00:00', 2, ["egy", "ketto"], 500, []);
		$this->assertEquals(1, $user->id());
		$this->assertEquals("user", $user->name());
		$this->assertEquals("username", $user->username());
		$this->assertEquals("1994-05-27 05:00:00", $user->valid());
		$this->assertEquals(2, $user->maximumMacSlots());
		$this->assertCount(2, $user->macAddresses());
		$this->assertEquals(500, $user->money());
		$this->assertCount(0, $user->freePages());
	}

	/** Function name: test_permission_attr
	 *
	 * This function is testing the EcnetUser data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_permission_attr(){
		$this->assertClassHasAttribute('id', EcnetUser::class);
		$this->assertClassHasAttribute('name', EcnetUser::class);
		$this->assertClassHasAttribute('username', EcnetUser::class);
		$this->assertClassHasAttribute('validTime', EcnetUser::class);
		$this->assertClassHasAttribute('maxMacSlotCount', EcnetUser::class);
		$this->assertClassHasAttribute('macAddresses', EcnetUser::class);
		$this->assertClassHasAttribute('money', EcnetUser::class);
		$this->assertClassHasAttribute('freePages', EcnetUser::class);
	}
}