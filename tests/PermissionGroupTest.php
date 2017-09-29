<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\PermissionGroup;

/** Class name: PermissionGroupTest
 *
 * This class is the PHPUnit test for the Data\PermissionGroup data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class PermissionGroupTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_permission_group
	 *
	 * This function is testing the PermissionGroup data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_permission_group(){
		$permissionGroup = new PermissionGroup(1, "alma", []);
		$this->assertEquals(1, $permissionGroup->id());
		$this->assertEquals("alma", $permissionGroup->name());
		$this->assertEquals([], $permissionGroup->permissions());

		$permissionGroup = new PermissionGroup("1", 2, []);
		$this->assertEquals(1, $permissionGroup->id());
		$this->assertEquals("2", $permissionGroup->name());
		$this->assertEquals([], $permissionGroup->permissions());
	}

	/** Function name: test_permission_group_attr
	 *
	 * This function is testing the PermissionGroup data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_permission_group_attr(){
		$this->assertClassHasAttribute('id', PermissionGroup::class);
		$this->assertClassHasAttribute('name', PermissionGroup::class);
		$this->assertClassHasAttribute('permissions', PermissionGroup::class);
	}
}