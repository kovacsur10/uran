<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\MacSlotOrder;

/** Class name: MacSlotOrderTest
 *
 * This class is the PHPUnit test for the Data\MacSlotOrder data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class MacSlotOrderTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_macslotorder
	 *
	 * This function is testing the MacSlotOrder data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_macslotorder(){
		$order = new MacSlotOrder(1, "why not?", "2016-05-27", "almafa");
		$this->assertEquals(1, $order->id());
		$this->assertEquals("why not?", $order->reason());
		$this->assertEquals("2016-05-27", $order->time());
		$this->assertEquals("almafa", $order->username());

		$order = new MacSlotOrder("20", "why not?", "2016-05-27", "almafa");
		$this->assertEquals(20, $order->id());
		$this->assertEquals("why not?", $order->reason());
		$this->assertEquals("2016-05-27", $order->time());
		$this->assertEquals("almafa", $order->username());
	}

	/** Function name: test_macslotorder_attr
	 *
	 * This function is testing the MacSlotOrder data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_macslotorder_attr(){
		$this->assertClassHasAttribute('id', MacSlotOrder::class);
		$this->assertClassHasAttribute('reason', MacSlotOrder::class);
		$this->assertClassHasAttribute('orderTime', MacSlotOrder::class);
		$this->assertClassHasAttribute('username', MacSlotOrder::class);
	}
}