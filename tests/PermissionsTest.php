<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\Modules;
use App\Classes\Data\Permission;
use App\Classes\Layout\Permissions;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValueMismatchException;
use App\Classes\Database;

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
		
		$permissions = Permissions::getForUser(null);
		$this->assertNotNull($permissions);
		$this->assertEquals([], $permissions);
	}
	
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
	
	function test_getById(){
		$this->assertNull(Permissions::getById(null));
		$this->assertNull(Permissions::getById(0));
		$permission = Permissions::getById(1);
		$this->assertNotNull($permission);
		$this->assertInstanceOf(Permission::class, $permission);
	}
	
	function test_getByName(){
		$this->assertNull(Permissions::getByName(null));
		$this->assertNull(Permissions::getByName(''));
		$this->assertNull(Permissions::getByName('almafa'));
		$permission = Permissions::getByName('tasks_add');
		$this->assertNotNull($permission);
		$this->assertInstanceOf(Permission::class, $permission);
	}
	
	function test_getAllPermissions(){
		try{
			$permissions = Permissions::getAllPermissions();
			$this->assertNotNull($permissions);
			$this->assertCount(15, $permissions);
			foreach($permissions as $permission){
				$this->assertInstanceOf(Permission::class, $permission);
			}
		}catch(\Exception $ex){
			$this->fail("Unexpected exception! ".$ex->getMessage());
		}
	}
	
	function test_getUsersWithPermission(){
		$this->assertCount(0, Permissions::getUsersWithPermission(''));
		$this->assertCount(0, Permissions::getUsersWithPermission(null));
		$this->assertCount(0, Permissions::getUsersWithPermission('not_existing_permission_name'));
		$this->assertCount(3, Permissions::getUsersWithPermission('ecnet_slot_verify'));
	}
	
	function test_hasGuestsDefaultPermission(){
		$this->assertFalse(Permissions::hasGuestsDefaultPermission(null));
		$this->assertFalse(Permissions::hasGuestsDefaultPermission(0));
		$this->assertFalse(Permissions::hasGuestsDefaultPermission(6));
		$this->assertTrue(Permissions::hasGuestsDefaultPermission(7));
		$this->assertFalse(Permissions::hasGuestsDefaultPermission('alma'));
	}
	
	function test_hasCollegistsDefaultPermission(){
		$this->assertFalse(Permissions::hasCollegistsDefaultPermission(null));
		$this->assertFalse(Permissions::hasCollegistsDefaultPermission(0));
		$this->assertFalse(Permissions::hasCollegistsDefaultPermission(6));
		$this->assertTrue(Permissions::hasCollegistsDefaultPermission(7));
		$this->assertFalse(Permissions::hasCollegistsDefaultPermission('alma'));
	}
	
	function test_setDefaults_success(){
		$this->assertFalse(Permissions::hasCollegistsDefaultPermission(5));
		$this->assertTrue(Permissions::hasCollegistsDefaultPermission(10));
		try{
			Permissions::setDefaults('collegist', [5, 10]);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertTrue(Permissions::hasCollegistsDefaultPermission(5));
		$this->assertTrue(Permissions::hasCollegistsDefaultPermission(10));
		
		$this->assertTrue(Permissions::hasGuestsDefaultPermission(7));
		try{
			Permissions::setDefaults('guest', []);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertFalse(Permissions::hasGuestsDefaultPermission(7));
	}
	
	function test_setDefaults_fail(){
		try{
			Permissions::setDefaults('collegist', [0, 2]);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception! ".$ex->getMessage());
		}
		
		try{
			Permissions::setDefaults("alma", [1,2]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Permissions::setDefaults("", [1,2]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Permissions::setDefaults('collegist', ['alma']);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception! ".$ex->getMessage());
		}
	}
	
	function test_setDefaults_null(){
		try{
			Permissions::setDefaults('collegist', null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception! ".$ex->getMessage());
		}
		
		try{
			Permissions::setDefaults(null, [1, 2]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception! ".$ex->getMessage());
		}
	}
}