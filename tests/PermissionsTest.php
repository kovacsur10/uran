<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\Modules;
use App\Classes\Data\Permission;
use App\Classes\Layout\Permissions;

/** Class name: PermissionsTest
 *
 * This class is the PHPUnit test for the Layout\Permissions model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class PermissionsTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_get
	 *
	 * This function is testing the get function of the Modules model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getForUser(){
		$permissions = Permissions::getForUser(41);
		$this->assertNotNull($permissions);
		$this->assertCount(3, $permissions);
		
		foreach($permissions as $permission){
			$this->assertInstanceOf(Permission::class, $permission);
		}
		
		$permissions = Permissions::getForUser(-1);
		$this->assertNotNull($permissions);
		$this->assertEquals([], $permissions);
	}
	
}