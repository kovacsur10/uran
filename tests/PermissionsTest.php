<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\Permission;
use App\Classes\Layout\Permissions;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\UserNotFoundException;

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

	/** Function name: test_getForUser
	 *
	 * This function is testing the getForUser function of the Permissions model.
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
		
		$permissions = Permissions::getForUser(null);
		$this->assertNotNull($permissions);
		$this->assertEquals([], $permissions);
	}
	
	/** Function name: test_permitted
	 *
	 * This function is testing the permitted function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_permitted(){
		$this->assertTrue(Permissions::permitted(41, 'ecnet_slot_verify'));
		$this->assertFalse(Permissions::permitted(41, 'tasks_add'));
		$this->assertFalse(Permissions::permitted(41, 'almafa'));
		$this->assertFalse(Permissions::permitted(41, ''));
		$this->assertFalse(Permissions::permitted(41, null));
		$this->assertFalse(Permissions::permitted(-1, 'ecnet_slot_verify'));
		$this->assertFalse(Permissions::permitted(null, 'ecnet_slot_verify'));
		$this->assertFalse(Permissions::permitted(null, null));
	}
	
	/** Function name: test_getById
	 *
	 * This function is testing the getById function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getById(){
		$this->assertNull(Permissions::getById(null));
		$this->assertNull(Permissions::getById(0));
		$permission = Permissions::getById(1);
		$this->assertNotNull($permission);
		$this->assertInstanceOf(Permission::class, $permission);
	}
	
	/** Function name: test_getByName
	 *
	 * This function is testing the getByName function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getByName(){
		$this->assertNull(Permissions::getByName(null));
		$this->assertNull(Permissions::getByName(''));
		$this->assertNull(Permissions::getByName('almafa'));
		$permission = Permissions::getByName('tasks_add');
		$this->assertNotNull($permission);
		$this->assertInstanceOf(Permission::class, $permission);
	}
	
	/** Function name: test_getAllPermissions
	 *
	 * This function is testing the getAllPermissions function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getAllPermissions(){
		try{
			$permissions = Permissions::getAllPermissions();
			$this->assertNotNull($permissions);
			$this->assertCount(16, $permissions);
			foreach($permissions as $permission){
				$this->assertInstanceOf(Permission::class, $permission);
			}
		}catch(\Exception $ex){
			$this->fail("Unexpected exception! ".$ex->getMessage());
		}
	}
	
	/** Function name: test_getUsersWithPermission
	 *
	 * This function is testing the getUsersWithPermission function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getUsersWithPermission(){
		$this->assertCount(0, Permissions::getUsersWithPermission(''));
		$this->assertCount(0, Permissions::getUsersWithPermission(null));
		$this->assertCount(0, Permissions::getUsersWithPermission('not_existing_permission_name'));
		$this->assertCount(3, Permissions::getUsersWithPermission('ecnet_slot_verify'));
	}
	
	/** Function name: test_removeAll_success
	 *
	 * This function is testing the removeAll function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_removeAll_success(){
		$this->assertTrue(Permissions::permitted(1, 'ecnet_slot_verify'));
		$this->assertTrue(Permissions::permitted(41, 'ecnet_slot_verify'));
		$this->assertFalse(Permissions::permitted(41, 'tasks_add'));
		try{
			Permissions::removeAll(41);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertTrue(Permissions::permitted(1, 'ecnet_slot_verify'));
		$this->assertFalse(Permissions::permitted(41, 'ecnet_slot_verify'));
		$this->assertFalse(Permissions::permitted(41, 'tasks_add'));
		$this->assertCount(0, Permissions::getForUser(41));
	}
	
	/** Function name: test_removeAll_fail
	 *
	 * This function is testing the removeAll function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_removeAll_fail(){
		try{
			Permissions::removeAll(-1);
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_removeAll_exceptions
	 *
	 * This function is testing the removeAll function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_removeAll_exceptions(){
		try{
			Permissions::removeAll(null);
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_setPermissionForUser_success
	 *
	 * This function is testing the removeAll function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setPermissionForUser_success(){
		$this->assertFalse(Permissions::permitted(41, 'tasks_add'));
		$this->assertCount(3, Permissions::getForUser(41));
		try{
			Permissions::setPermissionForUser(41, 10);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertTrue(Permissions::permitted(41, 'tasks_add'));
		$this->assertCount(4, Permissions::getForUser(41));
	}
	
	/** Function name: test_setPermissionForUser_fail
	 *
	 * This function is testing the removeAll function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setPermissionForUser_fail(){
		try{
			Permissions::setPermissionForUser(-1, 10);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			Permissions::setPermissionForUser(41, -1);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_setPermissionForUser_null
	 *
	 * This function is testing the removeAll function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setPermissionForUser_null(){
		try{
			Permissions::setPermissionForUser(null, 10);
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Permissions::setPermissionForUser(41, null);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Permissions::setPermissionForUser(null, null);
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
}