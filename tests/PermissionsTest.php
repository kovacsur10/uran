<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\Permission;
use App\Classes\Layout\Permissions;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\UserNotFoundException;
use App\Classes\Data\PermissionGroup;

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
		$this->assertCount(6, $permissions);
		
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
	
	/** Function name: test_getForUserExplicitPermissions
	 *
	 * This function is testing the getForUserExplicitPermissions function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getForUserExplicitPermissions(){
		$permissions = Permissions::getForUserExplicitPermissions(41);
		$this->assertNotNull($permissions);
		$this->assertCount(3, $permissions);
		
		foreach($permissions as $permission){
			$this->assertInstanceOf(Permission::class, $permission);
		}
		
		$permissions = Permissions::getForUserExplicitPermissions(-1);
		$this->assertNotNull($permissions);
		$this->assertEquals([], $permissions);
		
		$permissions = Permissions::getForUserExplicitPermissions(null);
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
		$this->assertFalse(Permissions::permitted(41, 'tasks_admin'));
		$this->assertFalse(Permissions::permitted(41, 'almafa'));
		$this->assertFalse(Permissions::permitted(41, ''));
		try{
			$this->assertFalse(Permissions::permitted(41, null));
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$this->assertFalse(Permissions::permitted(-1, 'ecnet_slot_verify'));
		try{
			$this->assertFalse(Permissions::permitted(null, 'ecnet_slot_verify'));
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		try{
			$this->assertFalse(Permissions::permitted(null, null));
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
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
		$this->assertFalse(Permissions::permitted(41, 'tasks_admin'));
		try{
			Permissions::removeAll(41);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertTrue(Permissions::permitted(1, 'ecnet_slot_verify'));
		$this->assertFalse(Permissions::permitted(41, 'ecnet_slot_verify'));
		$this->assertFalse(Permissions::permitted(41, 'tasks_admin'));
		$this->assertCount(0, Permissions::getForUserExplicitPermissions(41));
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
		$this->assertFalse(Permissions::permitted(41, 'tasks_admin'));
		$this->assertCount(3, Permissions::getForUserExplicitPermissions(41));
		try{
			Permissions::setPermissionForUser(41, 12);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertTrue(Permissions::permitted(41, 'tasks_admin'));
		$this->assertCount(4, Permissions::getForUserExplicitPermissions(41));
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
	
	/** Function name: test_getPermissionGroups
	 *
	 * This function is testing the getPermissionGroups function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getPermissionGroups(){
		try{
			$groups = Permissions::getPermissionGroups();
			$this->assertCount(3, $groups);
			foreach($groups as $group){
				$this->assertInstanceOf(PermissionGroup::class, $group);
			}
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_getPermissionGroup
	 *
	 * This function is testing the getPermissionGroup function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getPermissionGroup(){
		try{
			$group = Permissions::getPermissionGroup(1);
			$this->assertNotNull($group);
			$this->assertInstanceOf(PermissionGroup::class, $group);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			$group = Permissions::getPermissionGroup(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$group = Permissions::getPermissionGroup(0);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_setGroupPersmissions
	 *
	 * This function is testing the setGroupPersmissions function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setGroupPersmissions(){
		try{
			$this->assertCount(4, Permissions::getPermissionGroup(2)->permissions());
			Permissions::setGroupPersmissions(2, [2, 6, 7, 8, 10]);
			$group = Permissions::getPermissionGroup(2);
			$this->assertCount(5, $group->permissions());
			$shouldBeEmpty = [2, 6, 7, 8, 10];
			foreach($group->permissions() as $perm){
				if(($key = array_search($perm->id(), $shouldBeEmpty)) !== false){
					unset($shouldBeEmpty[$key]);
				}
			}
			$this->assertCount(0, $shouldBeEmpty);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			Permissions::setGroupPersmissions(2, 56);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Permissions::setGroupPersmissions(null, [2, 6, 7, 8, 10]);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Permissions::setGroupPersmissions(2, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Permissions::setGroupPersmissions(null, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_getGroupsForUser
	 *
	 * This function is testing the getGroupsForUser function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_getGroupsForUser(){
		try{
			$groups = Permissions::getGroupsForUser(1);
			$this->assertCount(3, $groups);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			Permissions::getGroupsForUser(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$groups = Permissions::getGroupsForUser(100);
			$this->assertCount(0, $groups);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_getForUserFromGroups
	 *
	 * This function is testing the getForUserFromGroups function of the Permissions model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_getForUserFromGroups(){
		try{
			$groups = Permissions::getForUserFromGroups(1);
			$this->assertCount(16, $groups);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			Permissions::getForUserFromGroups(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$groups = Permissions::getForUserFromGroups(100);
			$this->assertCount(0, $groups);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
}