<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\AssignmentTable;

/** Class name: CountryTest
 *
 * This class is the PHPUnit test for the Data\AssignmentTable data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class AssignmentTableTest extends BrowserKitTestCase
{
	use DatabaseTransactions;

	/** Function name: test_assignment_table
	 *
	 * This function is testing the AssignmentTable data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_assignment_table(){
		$ass_table = new AssignmentTable("1", "alma", false);
		$this->assertEquals(1, $ass_table->id());
		$this->assertEquals("alma", $ass_table->name());
		$this->assertFalse($ass_table->active());

		$ass_table = new AssignmentTable(1, 2, true);
		$this->assertEquals("1", $ass_table->id());
		$this->assertEquals("2", $ass_table->name());
		$this->assertTrue($ass_table->active());
	}

	/** Function name: test_assignment_table_attr
	 *
	 * This function is testing the AssignmentTable data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_assignment_table_attr(){
		$this->assertClassHasAttribute('id', AssignmentTable::class);
		$this->assertClassHasAttribute('name', AssignmentTable::class);
		$this->assertClassHasAttribute('active', AssignmentTable::class);
	}
}