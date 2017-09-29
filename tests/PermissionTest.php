<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\Permission;

/** Class name: PermissionTest
 *
 * This class is the PHPUnit test for the Data\Permission data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class PermissionTest extends BrowserKitTestCase
{
	use DatabaseTransactions;

	/** Function name: test_permission
	 *
	 * This function is testing the Permission data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_permission(){
		$faculty = new Permission(1, "alma", "desc");
		$this->assertEquals(1, $faculty->id());
		$this->assertEquals("alma", $faculty->name());
		$this->assertEquals("desc", $faculty->description());

		$faculty = new Permission("1", 2, 4);
		$this->assertEquals(1, $faculty->id());
		$this->assertEquals("2", $faculty->name());
		$this->assertEquals("4", $faculty->description());
	}

	/** Function name: test_permission_attr
	 *
	 * This function is testing the Permission data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_permission_attr(){
		$this->assertClassHasAttribute('id', Permission::class);
		$this->assertClassHasAttribute('name', Permission::class);
		$this->assertClassHasAttribute('description', Permission::class);
	}
}