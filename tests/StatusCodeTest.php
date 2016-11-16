<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\StatusCode;

/** Class name: StatusCodeTest
 *
 * This class is the PHPUnit test for the Data\StatusCode data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class StatusCodeTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_status_code
	 *
	 * This function is testing the StatusCode data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_status_code(){
		$status_code = new StatusCode(1, "alma");
		$this->assertEquals(1, $status_code->id());
		$this->assertEquals("alma", $status_code->statusName());

		$status_code = new StatusCode("1", 2);
		$this->assertEquals(1, $status_code->id());
		$this->assertEquals("2", $status_code->statusName());
	}

	/** Function name: test_status_code_attr
	 *
	 * This function is testing the StatusCode data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_status_code_attr(){
		$this->assertClassHasAttribute('id', StatusCode::class);
		$this->assertClassHasAttribute('status_name', StatusCode::class);
	}
}