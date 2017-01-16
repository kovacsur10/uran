<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Session;
use App\Classes\LayoutData;
use App\Classes\Layout\BaseData;
use App\Classes\Layout\Errors;
use App\Classes\Data\User;
use App\Classes\Layout\Rooms;
use App\Classes\Layout\Permissions;
use App\Classes\Layout\Registrations;
use App\Classes\Layout\Tasks;
use App\Classes\Layout\Modules;
use App\Classes\Layout\EcnetData;

/** Class name: LayoutDataTest
 *
 * This class is the PHPUnit test for the LayoutData model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class LayoutDataTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_class
	 *
	 * This function is testing the class of the LayoutData model itself.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_class(){
		$this->classHasAttribute('user', LayoutData::class);
		$this->classHasAttribute('room', LayoutData::class);
		$this->classHasAttribute('logged', LayoutData::class);
		$this->classHasAttribute('modules', LayoutData::class);
		$this->classHasAttribute('permissions', LayoutData::class);
		$this->classHasAttribute('language', LayoutData::class);
		$this->classHasAttribute('base', LayoutData::class);
		$this->classHasAttribute('registrations', LayoutData::class);
		$this->classHasAttribute('tasks', LayoutData::class);
		$this->classHasAttribute('errors', LayoutData::class);
		$this->classHasAttribute('route', LayoutData::class);
	}
	
	/** Function name: test_constructor
	 *
	 * This function is testing the constructor of the LayoutData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_constructor(){
		$layout = new LayoutData();
		$this->assertInstanceOf(BaseData::class, $layout->base());
		$this->assertInstanceOf(Errors::class, $layout->errors());
		$this->assertInstanceOf(\App\Classes\Layout\User::class, $layout->user());
		$this->assertInstanceOf(Rooms::class, $layout->room());
		$this->assertInstanceOf(Permissions::class, $layout->permissions());
		$this->assertInstanceOf(Registrations::class, $layout->registrations());
		$this->assertInstanceOf(Tasks::class, $layout->tasks());
		$this->assertInstanceOf(BaseData::class, $layout->base());
		$this->assertInstanceOf(Modules::class, $layout->modules());
		$this->assertFalse($layout->logged());
		$this->assertNull($layout->getRoute());
	}
	
	/** Function name: test_constructor_logged
	 *
	 * This function is testing the constructor of the LayoutData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_constructor_logged(){
		Session::set('user', \App\Classes\Layout\User::getUserData(1));
		$layout = new LayoutData();
		$this->assertInstanceOf(BaseData::class, $layout->base());
		$this->assertInstanceOf(Errors::class, $layout->errors());
		$this->assertInstanceOf(\App\Classes\Layout\User::class, $layout->user());
		$this->assertInstanceOf(Rooms::class, $layout->room());
		$this->assertInstanceOf(Permissions::class, $layout->permissions());
		$this->assertInstanceOf(Registrations::class, $layout->registrations());
		$this->assertInstanceOf(Tasks::class, $layout->tasks());
		$this->assertInstanceOf(BaseData::class, $layout->base());
		$this->assertInstanceOf(Modules::class, $layout->modules());
		$this->assertTrue($layout->logged());
		$this->assertNull($layout->getRoute());
	}
	
	/** Function name: test_setUser
	 *
	 * This function is testing the setUser function of the LayoutData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_setUser(){
		Session::set('user', \App\Classes\Layout\User::getUserData(1));
		$layout = new LayoutData();
		$this->assertInstanceOf(\App\Classes\Layout\User::class, $layout->user());
		$layout->setUser(new EcnetData(1));
		$this->assertInstanceOf(EcnetData::class, $layout->user());
	}
	
	/** Function name: test_language
	 *
	 * This function is testing the language function of the LayoutData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_language(){
		$layout = new LayoutData();
		$this->assertNotEquals($layout->language('user'), 'missing tag');
		$this->assertEquals($layout->language('this_key_is_obviously_not_a_valid_key'), 'missing tag');
	}
	
	/** Function name: test_language
	 *
	 * This function is testing the formatDate function of the LayoutData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_formatDate(){
		$layout = new LayoutData();
		$this->assertEquals('2016. 12. 05. 06:42:52', $layout->formatDate('2016-12-05 06:42:52'));
		$this->assertEquals('', $layout->formatDate(''));
		$this->assertEquals('alma', $layout->formatDate('alma'));
		$this->assertEquals(null, $layout->formatDate(null));
	}
	
	/** Function name: test_setLanguage
	 *
	 * This function is testing the setLanguage function of the LayoutData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_setLanguage(){
		if(Session::has('lang')){
			Session::forget('lang');
		}
		$this->assertFalse(Session::has('lang'));
		LayoutData::setLanguage('hu_HU');
		$this->assertTrue(Session::has('lang'));
		$this->assertEquals(Session::get('lang'), 'hu_HU');
		LayoutData::setLanguage('en_US');
		$this->assertTrue(Session::has('lang'));
		$this->assertEquals(Session::get('lang'), 'en_US');
		LayoutData::setLanguage(null);
		$this->assertTrue(Session::has('lang'));
		$this->assertEquals(Session::get('lang'), 'en_US');
	}
	
	/** Function name: test_lang
	 *
	 * This function is testing the lang function of the LayoutData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_lang(){
		if(Session::has('lang')){
			Session::forget('lang');
		}
		$this->assertFalse(Session::has('lang'));
		Session::put('lang', 'en_US');
		$this->assertEquals('en_US', Session::get('lang'));
		$this->assertEquals(LayoutData::lang(), 'en_US');
	}
	
}