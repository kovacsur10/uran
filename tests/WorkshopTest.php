<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\Workshop;

/** Class name: FacultyTest
 *
 * This class is the PHPUnit test for the Data\Workshop data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class WorkshopTest extends BrowserKitTestCase
{
	use DatabaseTransactions;

	/** Function name: test_workshop
	 *
	 * This function is testing the Workshop data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_workshop(){
		$workshop = new Workshop(1, "alma");
		$this->assertEquals(1, $workshop->id());
		$this->assertEquals("alma", $workshop->name());

		$workshop = new Workshop("1", 2);
		$this->assertEquals(1, $workshop->id());
		$this->assertEquals("2", $workshop->name());
	}

	/** Function name: test_workshop_attr
	 *
	 * This function is testing the Workshop data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_workshop_attr(){
		$this->assertClassHasAttribute('id', Workshop::class);
		$this->assertClassHasAttribute('name', Workshop::class);
	}
}