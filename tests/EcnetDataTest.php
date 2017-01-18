<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\EcnetData;
use App\Classes\Data\EcnetUser;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValueMismatchException;

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
		$this->assertClassHasAttribute('macAddresses', EcnetData::class);
		$this->assertClassHasAttribute('ecnetUsers', EcnetData::class);
		$this->assertClassHasAttribute('filters', EcnetData::class);
	
		$user = new EcnetData();
		$this->assertInstanceOf(EcnetData::class, $user);
		$this->assertNotNull(0, $user->ecnetUser());
		$this->assertNotNull(0, $user->validationTime());
		$this->assertEquals("2016-09-30 05:00:00", $user->validationTime());
		$this->assertCount(0, $user->macAddresses());
		$this->assertEquals('', $user->getNameFilter());
		$this->assertEquals('', $user->getUsernameFilter());
	
		$user = new EcnetData(1);
		$this->assertInstanceOf(EcnetData::class, $user);
		$this->assertCount(15, $user->permissions());
		$this->assertNotNull(0, $user->validationTime());
		$this->assertEquals("2016-09-30 05:00:00", $user->validationTime());
		$this->assertCount(1, $user->macAddresses());
		$this->assertEquals('', $user->getNameFilter());
		$this->assertEquals('', $user->getUsernameFilter());
	
		$user = new EcnetData(200);
		$this->assertInstanceOf(EcnetData::class, $user);
		$this->assertCount(0, $user->permissions());
		$this->assertNotNull(0, $user->validationTime());
		$this->assertEquals("2016-09-30 05:00:00", $user->validationTime());
		$this->assertCount(0, $user->macAddresses());
		$this->assertEquals('', $user->getNameFilter());
		$this->assertEquals('', $user->getUsernameFilter());
	}
	
	/** Function name: test_macAddressesOfUser
	 *
	 * This function is testing the macAddressesOfUser function of the EcnetData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_macAddressesOfUser(){
		$user = new EcnetData(0);
		$this->assertCount(1, $user->macAddressesOfUser(1));
		$this->assertCount(0, $user->macAddressesOfUser(0));
		$this->assertCount(0, $user->macAddressesOfUser(null));
		$this->assertCount(0, $user->macAddressesOfUser("alma"));
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
		$user = new EcnetData(1);
		$this->assertEquals(0, $user->ecnetUser()->money());
		$user->setMoneyForUser(1, 100);
		$user = new EcnetData(1);
		$this->assertEquals(100, $user->ecnetUser()->money());
		$user->setMoneyForUser(0, 100);
		$user = new EcnetData(1);
		$this->assertEquals(100, $user->ecnetUser()->money());
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
		$this->assertCount(1, $ecnetUser->macAddresses());
		try{
			EcnetData::deleteMacAddress("F4:33:CC:ff:53:61");
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$ecnetUser = new EcnetData(1);
		$this->assertCount(0, $ecnetUser->macAddresses());
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
		}catch(DatabaseException $ex){
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
			EcnetData::addMACSlotOrder(null, null);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::addMACSlotOrder(1, null);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::addMACSlotOrder(null, "reason");
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			EcnetData::addMACSlotOrder(0, "reason");
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
	
	}
}
	
?>