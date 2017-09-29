<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\Errors;

/** Class name: ErrorsTest
 *
 * This class is the PHPUnit test for the Layout\Errors model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class ErrorsTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_errors
	 *
	 * This function is testing the Errors class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_errors(){
		$this->assertClassHasAttribute('errors', Errors::class);
		$this->assertClassHasAttribute('old', Errors::class);
	}
	
	/** Function name: test_add
	 *
	 * This function is testing the add function of the Errors model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_add(){
		$errors = new Errors();
		$this->assertFalse($errors->has('alma'));
		$errors->add('alma', null);
		$this->assertTrue($errors->has('alma'));
		$errors->add('korte', 1);
		$this->assertTrue($errors->has('alma'));
		$this->assertTrue($errors->has('korte'));
		$errors->add('alma', 'str');
		$this->assertTrue($errors->has('alma'));
		$this->assertTrue($errors->has('korte'));
		$errors->add('', 'str');
		$this->assertTrue($errors->has('alma'));
		$this->assertTrue($errors->has('korte'));
		$this->assertTrue($errors->has(''));
		$errors->add(null, 'str');
		$this->assertTrue($errors->has('alma'));
		$this->assertTrue($errors->has('korte'));
		$this->assertTrue($errors->has(''));
		$this->assertTrue($errors->has(null));
	}
	
	/** Function name: test_has
	 *
	 * This function is testing the has function of the Errors model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_has(){
		$errors = new Errors();
		$this->assertFalse($errors->has(null));
		$this->assertFalse($errors->has(''));
		$this->assertFalse($errors->has('alma'));
		$errors->add('alma', null);
		$this->assertTrue($errors->has('alma'));
		$errors->add('alma', 1);
		$this->assertTrue($errors->has('alma'));
		$errors->add(null, 1);
		$this->assertTrue($errors->has(null));
	}
	
	/** Function name: test_get
	 *
	 * This function is testing the get function of the Errors model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_get(){
		$errors = new Errors();
		$this->assertNull($errors->get(null));
		$this->assertNull($errors->get(''));
		$this->assertNull($errors->get('alma'));
		$errors->add('alma', null);
		$this->assertEquals(null, $errors->get('alma'));
		$errors->add('alma', 1);
		$this->assertEquals(1, $errors->get('alma'));
		$errors->add('korte', 'str');
		$this->assertEquals('str', $errors->get('korte'));
		$errors->add(null, 3);
		$this->assertEquals(3, $errors->get(null));
		$errors->add(null, 5);
		$this->assertEquals(5, $errors->get(null));
		$errors->add('', 42);
		$this->assertEquals(42, $errors->get(''));
	}
	
	/** Function name: test_addOld
	 *
	 * This function is testing the addOld function of the Errors model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_addOld(){
		$errors = new Errors();
		$this->assertFalse($errors->hasOld('alma'));
		$errors->addOld('alma', null);
		$this->assertTrue($errors->hasOld('alma'));
		$errors->addOld('korte', 1);
		$this->assertTrue($errors->hasOld('alma'));
		$this->assertTrue($errors->hasOld('korte'));
		$errors->addOld('alma', 'str');
		$this->assertTrue($errors->hasOld('alma'));
		$this->assertTrue($errors->hasOld('korte'));
		$errors->addOld('', 'str');
		$this->assertTrue($errors->hasOld('alma'));
		$this->assertTrue($errors->hasOld('korte'));
		$this->assertTrue($errors->hasOld(''));
		$errors->addOld(null, 'str');
		$this->assertTrue($errors->hasOld('alma'));
		$this->assertTrue($errors->hasOld('korte'));
		$this->assertTrue($errors->hasOld(''));
		$this->assertTrue($errors->hasOld(null));
	}
	
	/** Function name: test_hasOld
	 *
	 * This function is testing the hasOld function of the Errors model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_hasOld(){
		$errors = new Errors();
		$this->assertFalse($errors->hasOld(null));
		$this->assertFalse($errors->hasOld(''));
		$this->assertFalse($errors->hasOld('alma'));
		$errors->addOld('alma', null);
		$this->assertTrue($errors->hasOld('alma'));
		$errors->addOld('alma', 1);
		$this->assertTrue($errors->hasOld('alma'));
		$errors->addOld('alma', 1);
		$this->assertTrue($errors->hasOld('alma'));
		$errors->addOld(null, 1);
		$this->assertTrue($errors->hasOld(null));
	}
	
	/** Function name: test_getOld
	 *
	 * This function is testing the getOld function of the Errors model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getOld(){
		$errors = new Errors();
		$this->assertNull($errors->getOld(null));
		$this->assertNull($errors->getOld(''));
		$this->assertNull($errors->getOld('alma'));
		$errors->addOld('alma', null);
		$this->assertEquals(null, $errors->getOld('alma'));
		$errors->addOld('alma', 1);
		$this->assertEquals(1, $errors->getOld('alma'));
		$errors->addOld('korte', 'str');
		$this->assertEquals('str', $errors->getOld('korte'));
		$errors->addOld(null, 3);
		$this->assertEquals(3, $errors->getOld(null));
		$errors->addOld(null, 5);
		$this->assertEquals(5, $errors->getOld(null));
		$errors->addOld('', 42);
		$this->assertEquals(42, $errors->getOld(''));
	}
}