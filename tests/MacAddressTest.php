<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\MacAddress;

/** Class name: MacAddressTest
 *
 * This class is the PHPUnit test for the Data\MacAddress data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class MacAddressTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_mac_address
	 *
	 * This function is testing the MacAddress data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_mac_address(){
		$status_code = new MacAddress(1, "alma");
		$this->assertEquals(1, $status_code->id());
		$this->assertEquals("alma", $status_code->address());

		$status_code = new MacAddress("1", 2);
		$this->assertEquals(1, $status_code->id());
		$this->assertEquals("2", $status_code->address());
	}

	/** Function name: test_mac_address_attr
	 *
	 * This function is testing the MacAddress data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_mac_address_attr(){
		$this->assertClassHasAttribute('id', MacAddress::class);
		$this->assertClassHasAttribute('address', MacAddress::class);
	}
}