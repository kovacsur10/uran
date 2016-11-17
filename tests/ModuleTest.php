<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\Module;

/** Class name: ModuleTest
 *
 * This class is the PHPUnit test for the Data\Module data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class ModuleTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_module
	 *
	 * This function is testing the Module data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_module(){
		$module = new Module(1, "alma");
		$this->assertEquals(1, $module->id());
		$this->assertEquals("alma", $module->name());
		$this->assertNull($module->isActive());

		$module = new Module("1", 2);
		$this->assertEquals(1, $module->id());
		$this->assertEquals("2", $module->name());
		$this->assertNull($module->isActive());
		
		$module = new Module(1, "mod", false);
		$this->assertEquals(1, $module->id());
		$this->assertEquals("mod", $module->name());
		$this->assertFalse($module->isActive());
		
		$module = new Module(1, "mod", true);
		$this->assertEquals(1, $module->id());
		$this->assertEquals("mod", $module->name());
		$this->assertTrue($module->isActive());
	}

	/** Function name: test_module_attr
	 *
	 * This function is testing the Module data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_module_attr(){
		$this->assertClassHasAttribute('id', Module::class);
		$this->assertClassHasAttribute('name', Module::class);
		$this->assertClassHasAttribute('active', Module::class);
	}
}