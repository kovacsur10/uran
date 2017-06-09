<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Session;
use App\Classes\Layout\EcnetData;
use App\Classes\Data\EcnetUser;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValueMismatchException;
use App\Classes\Data\MacSlotOrder;
use App\Persistence\P_Ecnet;

/** Class name: EcnetDataTest
 *
 * This class is the PHPUnit test for the Layout\EcnetData data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class EcnetDataTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_class
	 *
	 * This function is testing the class itself and the constructor of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_class(){
		$this->assertClassHasAttribute('ecnetUser', EcnetData::class);
		$this->assertClassHasAttribute('validationTime', EcnetData::class);
		$this->assertClassHasAttribute('ecnetUsers', EcnetData::class);
		$this->assertClassHasAttribute('filters', EcnetData::class);
	
		$user = new EcnetData();
		$this->assertInstanceOf(EcnetData::class, $user);
		$this->assertNotNull(0, $user->ecnetUser());
		$this->assertNotNull(0, $user->validationTime());
		$this->assertEquals("2016-09-30 05:00:00", $user->validationTime());
		$this->assertEquals('', $user->getNameFilter());
		$this->assertEquals('', $user->getUsernameFilter());
	
		$user = new EcnetData(1);
		$this->assertInstanceOf(EcnetData::class, $user);
		$this->assertCount(16, $user->permissions());
		$this->assertNotNull(0, $user->validationTime());
		$this->assertEquals("2016-09-30 05:00:00", $user->validationTime());
		$this->assertEquals('', $user->getNameFilter());
		$this->assertEquals('', $user->getUsernameFilter());
	
		$user = new EcnetData(200);
		$this->assertInstanceOf(EcnetData::class, $user);
		$this->assertCount(0, $user->permissions());
		$this->assertNotNull(0, $user->validationTime());
		$this->assertEquals("2016-09-30 05:00:00", $user->validationTime());
		$this->assertEquals('', $user->getNameFilter());
		$this->assertEquals('', $user->getUsernameFilter());
	}
	
	/** Function name: test_register
	 *
	 * This function is testing the register function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_register(){
		$user = new EcnetData(0);
		try{
			$user->register(null);
			$this->fail("An exception was expected!");
		}catch(\Exception $ex){
		}
		
		try{
			$user->register();
			$this->fail("An exception was expected!");
		}catch(\Exception $ex){
		}
		
		try{
			$user->register(1);
			$this->fail("An exception was expected!");
		}catch(\Exception $ex){
		}
		
		try{
			$user->register(200);
			$this->fail("An exception was expected!");
		}catch(\Exception $ex){
		}
	}
	
	/** Function name: test_getEcnetUserData
	 *
	 * This function is testing the getEcnetUserData function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getEcnetUserData(){
		try{
			$user = EcnetData::getEcnetUserData(null);
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$user = EcnetData::getEcnetUserData(200);
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$user = EcnetData::getEcnetUserData(1);
			$this->assertInstanceOf(EcnetUser::class, $user);
			$this->assertCount(2, $user->freePages());
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}

	/** Function name: test_setMoneyForUser
	 *
	 * This function is testing the setMoneyForUser function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setMoneyForUser(){
		try{
			EcnetData::setMoneyForUser(null, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	
		try{
			EcnetData::setMoneyForUser(null, 200);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	
		try{
			EcnetData::setMoneyForUser(1, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	
		$ecnetUser = new EcnetData(1);
		$this->assertEquals(0, $ecnetUser->ecnetUser()->money());
		try{
			EcnetData::setMoneyForUser(1, 120);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$ecnetUser = new EcnetData(1);
		$this->assertEquals(120, $ecnetUser->ecnetUser()->money());
		try{
			EcnetData::setMoneyForUser(0, 200);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$ecnetUser = new EcnetData(1);
		$this->assertEquals(120, $ecnetUser->ecnetUser()->money());
	}
	
	/** Function name: test_addFreePagesForUser
	 *
	 * This function is testing the addFreePagesForUser function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_addFreePagesForUser(){
		try{
			EcnetData::addFreePagesForUser(null, null, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::setMoneyForUser(null, 200, "");
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::setMoneyForUser(1, null, "");
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::setMoneyForUser(1, 200, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		//TODO: from here
		$ecnetUser = new EcnetData(1);
		$this->assertEquals(0, $ecnetUser->ecnetUser()->money());
		try{
			EcnetData::setMoneyForUser(1, 120, null);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$ecnetUser = new EcnetData(1);
		$this->assertEquals(120, $ecnetUser->ecnetUser()->money());
		try{
			EcnetData::setMoneyForUser(0, 200);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$ecnetUser = new EcnetData(1);
		$this->assertEquals(120, $ecnetUser->ecnetUser()->money());
	}
	
	/** Function name: test_changeDefaultValidDate_null
	 *
	 * This function is testing the changeDefaultValidDate function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_changeDefaultValidDate_null(){
		try{
			EcnetData::changeDefaultValidDate(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_changeDefaultValidDate_fail_1
	 *
	 * This function is testing the changeDefaultValidDate function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_changeDefaultValidDate_fail_1(){
		try{
			EcnetData::changeDefaultValidDate('');
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_changeDefaultValidDate_fail_2
	 *
	 * This function is testing the changeDefaultValidDate function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_changeDefaultValidDate_fail_2(){
		try{
			EcnetData::changeDefaultValidDate('almafa');
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_changeDefaultValidDate_success
	 *
	 * This function is testing the changeDefaultValidDate function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_changeDefaultValidDate_success(){				
		try{
			EcnetData::changeDefaultValidDate('2020-10-10 05:32:33');
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$ecnetData = new EcnetData(0);
		$this->assertEquals('2020-10-10 05:32:33', $ecnetData->validationTime());
	}
	
	/** Function name: test_activateUserNet
	 *
	 * This function is testing the activateUserNet function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_activateUserNet(){
		try{
			EcnetData::activateUserNet(null, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::activateUserNet(null, "1994-05-27 12:23:53");
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::activateUserNet(1, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::activateUserNet(1, "1994-06-20");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::activateUserNet(1, "1994-06-20 04:55:34");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$ecnetUser = new EcnetData(1);
		$this->assertEquals("1994-06-20 04:55:34", $ecnetUser->ecnetUser()->valid());
	}
	
	/** Function name: test_macAddressExists
	 *
	 * This function is testing the macAddressExists function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_macAddressExists(){
		$this->assertFalse(EcnetData::macAddressExists(null));
		$this->assertFalse(EcnetData::macAddressExists("null"));
		$this->assertFalse(EcnetData::macAddressExists("AA:AA:AA:AA:AA:AA"));
		$this->assertFalse(EcnetData::macAddressExists("AA-AA-AA-AA-AA-AA"));
		$this->assertTrue(EcnetData::macAddressExists("F4:33:CC:FF:53:61"));
		$this->assertTrue(EcnetData::macAddressExists("F4:33:CC:ff:53:61"));
	}
	
	/** Function name: test_deleteMacAddress_null
	 *
	 * This function is testing the deleteMacAddress function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_deleteMacAddress_null(){
		try{
			EcnetData::deleteMacAddress(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_deleteMacAddress
	 *
	 * This function is testing the deleteMacAddress function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_deleteMacAddress(){	
		try{
			EcnetData::deleteMacAddress("AA:AA:AA:AA:AA:AA");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::deleteMacAddress("");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		$ecnetUser = new EcnetData(1);
		$this->assertCount(1, $ecnetUser->ecnetUser()->macAddresses());
		try{
			EcnetData::deleteMacAddress("F4:33:CC:FF:53:61");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$ecnetUser = new EcnetData(1);
		$this->assertCount(0, $ecnetUser->ecnetUser()->macAddresses());
	}
	
	/** Function name: test_insertMacAddress_fail
	 *
	 * This function is testing the insertMacAddress function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_insertMacAddress_fail(){
		try{
			EcnetData::insertMacAddress(null, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::insertMacAddress(null, "AA:AA:AA:AA:AA:AA");
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::insertMacAddress(1, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::insertMacAddress(25, "F4:33:CC:ff:53:61");
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::insertMacAddress(1, "almafa");
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::insertMacAddress(0, "AA:AA:AA:AA:AA:AA");
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_insertMacAddress_success
	 *
	 * This function is testing the insertMacAddress function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_insertMacAddress_success(){
		try{
			EcnetData::insertMacAddress(1, "AA:AA:AA:AA:AA:AA");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::insertMacAddress(1, "AA:AA:bb:AA:AA:AA");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_hasMACSlotOrder
	 *
	 * This function is testing the hasMACSlotOrder function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_hasMACSlotOrder(){
		$this->assertFalse(EcnetData::hasMACSlotOrder(0));
		$this->assertFalse(EcnetData::hasMACSlotOrder(1));
		$this->assertFalse(EcnetData::hasMACSlotOrder(null));
		$this->assertTrue(EcnetData::hasMACSlotOrder(25));
	}
	
	/** Function name: test_addMACSlotOrder_null
	 *
	 * This function is testing the addMACSlotOrder function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_addMACSlotOrder_null(){
		try{
			EcnetData::addMACSlotOrder(null, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::addMACSlotOrder(1, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::addMACSlotOrder(null, "reason");
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_addMACSlotOrder
	 *
	 * This function is testing the addMACSlotOrder function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_addMACSlotOrder(){		
		try{
			EcnetData::addMACSlotOrder(200, "reason");
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::addMACSlotOrder(1, "reason");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_getMacSlotOrders
	 *
	 * This function is testing the getMacSlotOrders function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getMacSlotOrders(){
		$orders = EcnetData::getMacSlotOrders();
		$this->assertCount(1, $orders);
		foreach($orders as $order){
			$this->assertInstanceOf(MacSlotOrder::class, $order);
		}
	}
	
	/** Function name: test_getMacSlotOrderById
	 *
	 * This function is testing the getMacSlotOrderById function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getMacSlotOrderById(){
		try{
			EcnetData::getMacSlotOrderById(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$this->assertNull(EcnetData::getMacSlotOrderById(5));
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			$order = EcnetData::getMacSlotOrderById(26);
			$this->assertNotNull($order);
			$this->assertInstanceOf(MacSlotOrder::class, $order);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_setMacSlotCountForUser
	 *
	 * This function is testing the setMacSlotCountForUser function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setMacSlotCountForUser(){
		try{
			EcnetData::setMacSlotCountForUser(null, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::setMacSlotCountForUser(null, 2);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::setMacSlotCountForUser(1, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::setMacSlotCountForUser(1, -1);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::setMacSlotCountForUser(200, 3);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		$this->assertEquals(3, EcnetData::getEcnetUserData(1)->maximumMacSlots());
		try{
			EcnetData::setMacSlotCountForUser(1, 6);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertEquals(6, EcnetData::getEcnetUserData(1)->maximumMacSlots());
	}
	
	/** Function name: test_deleteMacSlotOrderById
	 *
	 * This function is testing the deleteMacSlotOrderById function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_deleteMacSlotOrderById(){
		try{
			EcnetData::deleteMacSlotOrderById(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){		
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		$this->assertCount(1, EcnetData::getMacSlotOrders());
		try{
			EcnetData::deleteMacSlotOrderById(200);
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$this->assertCount(1, EcnetData::getMacSlotOrders());
		try{
			EcnetData::deleteMacSlotOrderById(26);
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$this->assertCount(0, EcnetData::getMacSlotOrders());
	}
	
	/** Function name: test_filterUsers
	 *
	 * This function is testing the filterUsers function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function filterUsers(){
		$user = new EcnetData(0);
		$this->assertCount(8, $user->ecnetUsers(0,1000));
		$this->assertNull($user->getNameFilter());
		$this->assertNull($user->getUsernameFilter());
		
		Session::flush();
		$this->filterUsers();
		$this->assertCount(8, $user->ecnetUsers(0,1000));
		$this->assertNull($user->getNameFilter());
		$this->assertNull($user->getUsernameFilter());
		
		Session::flush();
		Session::put('ecnet_username_filter', 'a');
		$this->filterUsers();
		$this->assertCount(8, $user->ecnetUsers(0,1000));
		$this->assertNull($user->getNameFilter());
		$this->assertNull($user->getUsernameFilter());
		
		Session::flush();
		Session::put('ecnet_name_filter', 'a');
		$this->filterUsers();
		$this->assertCount(8, $user->ecnetUsers(0,1000));
		$this->assertNull($user->getNameFilter());
		$this->assertNull($user->getUsernameFilter());
		
		Session::flush();
		Session::put('ecnet_username_filter', 'a');
		Session::put('ecnet_name_filter', '');
		$this->filterUsers();
		$this->assertCount(3, $user->ecnetUsers(0,1000));
		$this->assertEquals('ecnet_name_filter', $user->getNameFilter());
		$this->assertEquals('ecnet_name_filter', $user->getUsernameFilter());
	}
	
	/** Function name: test_setFilterUsers
	 *
	 * This function is testing the setFilterUsers function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setFilterUsers(){
		$this->assertFalse(Session::has('ecnet_username_filter'));
		$this->assertFalse(Session::has('ecnet_name_filter'));
		EcnetData::setFilterUsers(null, null);
		$this->assertTrue(Session::has('ecnet_username_filter'));
		$this->assertTrue(Session::has('ecnet_name_filter'));
		$this->assertEquals("", Session::get('ecnet_username_filter'));
		$this->assertEquals("", Session::get('ecnet_name_filter'));
		
		Session::flush();
		
		$this->assertFalse(Session::has('ecnet_username_filter'));
		$this->assertFalse(Session::has('ecnet_name_filter'));
		EcnetData::setFilterUsers("alma", null);
		$this->assertTrue(Session::has('ecnet_username_filter'));
		$this->assertTrue(Session::has('ecnet_name_filter'));
		$this->assertEquals("alma", Session::get('ecnet_username_filter'));
		$this->assertEquals("", Session::get('ecnet_name_filter'));
		
		Session::flush();
		
		$this->assertFalse(Session::has('ecnet_username_filter'));
		$this->assertFalse(Session::has('ecnet_name_filter'));
		EcnetData::setFilterUsers(null, "hal");
		$this->assertTrue(Session::has('ecnet_username_filter'));
		$this->assertTrue(Session::has('ecnet_name_filter'));
		$this->assertEquals("", Session::get('ecnet_username_filter'));
		$this->assertEquals("hal", Session::get('ecnet_name_filter'));
		
		Session::flush();
		
		$this->assertFalse(Session::has('ecnet_username_filter'));
		$this->assertFalse(Session::has('ecnet_name_filter'));
		EcnetData::setFilterUsers("alma", "hal");
		$this->assertTrue(Session::has('ecnet_username_filter'));
		$this->assertTrue(Session::has('ecnet_name_filter'));
		$this->assertEquals("alma", Session::get('ecnet_username_filter'));
		$this->assertEquals("hal", Session::get('ecnet_name_filter'));
	}
	
	/** Function name: test_resetFilterUsers
	 *
	 * This function is testing the resetFilterUsers function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_resetFilterUsers(){
		Session::flush();
		
		$this->assertFalse(Session::has('ecnet_username_filter'));
		$this->assertFalse(Session::has('ecnet_name_filter'));
		EcnetData::resetFilterUsers();
		$this->assertFalse(Session::has('ecnet_username_filter'));
		$this->assertFalse(Session::has('ecnet_name_filter'));
		
		Session::flush();
		Session::put('ecnet_username_filter', 'alma');
		Session::put('ecnet_name_filter', 'alma2');
		Session::put('no_key_like_this', 'alma2');
		
		$this->assertTrue(Session::has('ecnet_username_filter'));
		$this->assertTrue(Session::has('ecnet_name_filter'));
		$this->assertTrue(Session::has('no_key_like_this'));
		EcnetData::resetFilterUsers();
		$this->assertFalse(Session::has('ecnet_username_filter'));
		$this->assertFalse(Session::has('ecnet_name_filter'));
		$this->assertTrue(Session::has('no_key_like_this'));
	}
	
	/** Function name: test_checkUserCount
	 *
	 * This function is testing the checkUserCount function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_checkUserCount(){
		Session::flush();
		$this->assertFalse(Session::has('ecnet_admin_paging'));
		$this->assertEquals(50, EcnetData::checkUserCount(null));
		
		Session::flush();
		Session::put('ecnet_admin_paging', 20);
		$this->assertTrue(Session::has('ecnet_admin_paging'));
		$this->assertEquals(20, EcnetData::checkUserCount(null));
		
		Session::flush();
		$this->assertFalse(Session::has('ecnet_admin_paging'));
		$this->assertEquals(50, EcnetData::checkUserCount(0));
		
		Session::flush();
		$this->assertFalse(Session::has('ecnet_admin_paging'));
		$this->assertEquals(50, EcnetData::checkUserCount(501));
		
		Session::flush();
		Session::put('ecnet_admin_paging', 20);
		$this->assertTrue(Session::has('ecnet_admin_paging'));
		$this->assertEquals(50, EcnetData::checkUserCount(0));
		
		Session::flush();
		$this->assertFalse(Session::has('ecnet_admin_paging'));
		$this->assertEquals(40, EcnetData::checkUserCount(40));
		
		Session::flush();
		Session::put('ecnet_admin_paging', 20);
		$this->assertTrue(Session::has('ecnet_admin_paging'));
		$this->assertEquals(60, EcnetData::checkUserCount(60));
	}
	
	/** Function name: test_getSessionData
	 *
	 * This function is testing the getSessionData function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getSessionData(){
		Session::flush();
		$this->assertCount(0, EcnetData::getSessionData());
		
		Session::flush();
		Session::put('ecnet_username_filter', 20);
		Session::put('ecnet_name_filter', 15);
		Session::put('no_key_like_this', 40);
		$this->assertEquals(['ecnet_username_filter' => 20, 'ecnet_name_filter' => 15], EcnetData::getSessionData());
	}
	
	/** Function name: test_manageMacAddresses
	 *
	 * This function is testing the manageMacAddresses function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_manageMacAddresses(){
		$ecnet = new EcnetData(1);
		//bad arguments
		try{
			$ecnet->manageMacAddresses(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		try{
			$ecnet->manageMacAddresses(8);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		$ecnet = new EcnetData(1);
		try{
			$ecnet->manageMacAddresses(["F4:33:CC:FF:53:61"]);
			$user = P_Ecnet::getUser(1);
			$addr = $user->macAddresses();
			$this->assertCount(1, $addr);
			$this->assertEquals("F4:33:CC:FF:53:61", $addr[0]->address());
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		$ecnet = new EcnetData(1);
		try{
			$ecnet->manageMacAddresses([]);
			$user = P_Ecnet::getUser(1);
			$this->assertCount(0, $user->macAddresses());
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		$ecnet = new EcnetData(1);
		try{
			$ecnet->manageMacAddresses(["F4:33:CC:FF:53:61"]);
			$user = P_Ecnet::getUser(1);
			$addr = $user->macAddresses();
			$this->assertCount(1, $addr);
			$this->assertEquals("F4:33:CC:FF:53:61", $addr[0]->address());
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		$ecnet = new EcnetData(1);
		try{
			$ecnet->manageMacAddresses(["F4:33:CC:FF:53:63", "F4:33:CC:FF:53:61"]);
			$user = P_Ecnet::getUser(1);
			$addr = $user->macAddresses();
			$this->assertCount(2, $addr);
			foreach($addr as $mac){
				$this->assertTrue("F4:33:CC:FF:53:61" === $mac->address() || "F4:33:CC:FF:53:63" === $mac->address());
			}
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		$ecnet = new EcnetData(1);
		try{
			$ecnet->manageMacAddresses(["F4:33:CC:FF:53:63", "F4:33:CC:FF:53:69"]);
			$user = P_Ecnet::getUser(1);
			$addr = $user->macAddresses();
			$this->assertCount(2, $addr);
			foreach($addr as $mac){
				$this->assertTrue("F4:33:CC:FF:53:69" === $mac->address() || "F4:33:CC:FF:53:63" === $mac->address());
			}
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		$ecnet = new EcnetData(1);
		try{
			$ecnet->manageMacAddresses([]);
			$user = P_Ecnet::getUser(1);
			$this->assertCount(0, $user->macAddresses());
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
	}
}
	
?>