<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\Modules;
use App\Classes\Data\Module;
use App\Exceptions\DatabaseException;

/** Class name: ModulesTest
 *
 * This class is the PHPUnit test for the Layout\Modules model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class ModulesTest extends TestCase
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
	function test_get(){
		$modules = Modules::get();
		$this->assertNotNull($modules);
		$this->assertCount(5, $modules);
		foreach($modules as $module){
			$this->assertInstanceOf(Module::class, $module);
		}
	}
	
	/** Function name: test_getById
	 *
	 * This function is testing the getById function of the Modules model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getById(){
		$this->assertNull(Modules::getById(null));
		$module = Modules::getById(0);
		$this->assertNull($module);
		$module = Modules::getById(1);
		$this->assertNotNull($module);
		$this->assertEquals(1, $module->id());
	}
	
	/** Function name: test_isActivatedById
	 *
	 * This function is testing the isActivatedById function of the Modules model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_isActivatedById(){
		$this->assertFalse(Modules::isActivatedById(null));
		$this->assertFalse(Modules::isActivatedById(0));
		$this->assertTrue(Modules::isActivatedById(1));
		$this->assertFalse(Modules::isActivatedById(5));
	}
	
	/** Function name: test_isActivatedByName
	 *
	 * This function is testing the isActivatedByName function of the Modules model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_isActivatedByName(){
		$this->assertFalse(Modules::isActivatedByName(null));
		$this->assertFalse(Modules::isActivatedByName(''));
		$this->assertFalse(Modules::isActivatedByName('no_module_like_this'));
		$this->assertTrue(Modules::isActivatedByName('ecnet'));
		$this->assertFalse(Modules::isActivatedByName('always_inactive'));
	}
	
	/** Function name: test_getActives
	 *
	 * This function is testing the getActives function of the Modules model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getActives(){
		$modules = Modules::getActives();
		$this->assertNotNull($modules);
		$this->assertCount(4, $modules);
		foreach($modules as $module){
			$this->assertInstanceOf(Module::class, $module);
		}
	}
	
	/** Function name: test_getInactives
	 *
	 * This function is testing the getInactives function of the Modules model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getInactives(){
		$modules = Modules::getInactives();
		$this->assertNotNull($modules);
		$this->assertCount(1, $modules);
		foreach($modules as $module){
			$this->assertInstanceOf(Module::class, $module);
		}
	}
	
	/** Function name: test_activate_success
	 *
	 * This function is testing the activate function of the Modules model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_activate_success(){
		$modules = Modules::getInactives();
		$this->assertNotNull($modules);
		$this->assertCount(1, $modules);
		try{
			Modules::activate(5);
		}catch(\Exception $ex){
			$this->assertFail("Database exception was not expected! ".$ex->getMessage());
		}
		$modules = Modules::getInactives();
		$this->assertNotNull($modules);
		$this->assertCount(0, $modules);
	}
	
	/** Function name: test_activate_fail
	 *
	 * This function is testing the activate function of the Modules model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_activate_fail(){
		try{
			Modules::activate(-1);
			$this->fail("An exception should be thrown!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected database exception! ".$ex->getMessage());
		}
		$this->assertTrue(true); //No exceptions, so pass it. - from PHPUnit 6, no assertion is reported as a risk
	}
	
	/** Function name: test_activate_null
	 *
	 * This function is testing the activate function of the Modules model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_activate_null(){
		try{
			Modules::activate(null);
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected database exception! ".$ex->getMessage());
		}
		$this->assertTrue(true); //No exceptions, so pass it. - from PHPUnit 6, no assertion is reported as a risk
	}
	
	/** Function name: test_deactivate_success
	 *
	 * This function is testing the deactivate function of the Modules model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_deactivate_success(){
		$modules = Modules::getInactives();
		$this->assertNotNull($modules);
		$this->assertCount(1, $modules);
		try{
			Modules::deactivate(1);
		}catch(\Exception $ex){
			$this->assertFail("Database exception was not expected! ".$ex->getMessage());
		}
		$modules = Modules::getInactives();
		$this->assertNotNull($modules);
		$this->assertCount(2, $modules);
	}
	
	/** Function name: test_deactivate_null
	 *
	 * This function is testing the activate function of the Modules model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_deactivate_null(){
		$modules = Modules::getActives();
		$this->assertNotNull($modules);
		$this->assertCount(4, $modules);
		try{
			Modules::deactivate(null);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception! ".$ex->getMessage());
		}
		$modules = Modules::getActives();
		$this->assertNotNull($modules);
		$this->assertCount(4, $modules);
	}

}