<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Contracts\Session\Session;
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
use App\Classes\Data\StatusCode;
use App\Classes\Database;

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
		$this->assertTrue(true); //All attributes are okay... from PHPUnit 6, no assertion is reported as a risk
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
		session(['user' => \App\Classes\Layout\User::getUserData(1)]);
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
		session(['user' => \App\Classes\Layout\User::getUserData(1)]);
		$layout = new LayoutData();
		$this->assertInstanceOf(\App\Classes\Layout\User::class, $layout->user());
		$layout->setUser(new EcnetData(1));
		$this->assertInstanceOf(EcnetData::class, $layout->user());
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
		$this->assertEquals('2016. 12. 05. 06:42:52', $layout->formatDate('2016-12-05. 06:42:52'));
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
		LayoutData::setLanguage('hu');
		$this->assertEquals('hu', \App::getLocale());
		LayoutData::setLanguage('en');
		$this->assertEquals('en', \App::getLocale());
		LayoutData::setLanguage(null);
		$this->assertEquals('en', \App::getLocale());
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
		$this->assertEquals('hu', LayoutData::lang());
		LayoutData::setLanguage('en');
		$this->assertEquals('en', LayoutData::lang());
	}
	
	/** Function name: test_saveSession
	 *
	 * This function is testing the saveSession function of the LayoutData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_saveSession(){
		//user fake "login"
		$user = new User(34, "", "", "", "", "", new StatusCode(1, ""), "", true, true, "", "", "", "", "", "", "", "", null, null, "", true);
		session()->put('user', $user);
		//test session data to save
		session()->flush();
		session()->put('user', $user);
		
		Database::transaction(function(){
			LayoutData::loadSession(); //ensure that it's empty
			$this->assertCount(1, session()->all());
			LayoutData::saveSession();
			$this->assertCount(1, session()->all());
			LayoutData::loadSession(); //ensure that it was okay
			$this->assertCount(1, session()->all());
		});
			
		session()->flush();
		session()->put('user', $user);
		session()->put('tasks_caption_filter', 'asd');
		session()->put('sajt', 'asd');
		session()->put('ecnet_username_filter', 'omg');
		Database::transaction(function() use($user){
			$this->assertCount(4, session()->all());
			LayoutData::loadSession(); //ensure that it's empty
			$this->assertCount(4, session()->all());
			LayoutData::saveSession();
			$this->assertCount(4, session()->all());
			session()->flush();
			session()->put('user', $user);
			$this->assertCount(1, session()->all());
			LayoutData::loadSession(); //ensure that it was okay
			$this->assertCount(3, session()->all());
		});
	}
	
	/** Function name: test_loadSession
	 *
	 * This function is testing the loadSession function of the LayoutData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_loadSession(){
		session()->flush();
		//user fake "login"
		session()->put('user', new User(41, "", "", "", "", "", new StatusCode(1, ""), "", true, true, "", "", "", "", "", "", "", "", null, null, "", true));
		
		$this->assertCount(1, session()->all());
		LayoutData::loadSession();
		$this->assertCount(3, session()->all());
		
		session()->flush();
		//user fake "login"
		session()->put('user', new User(20, "", "", "", "", "", new StatusCode(1, ""), "", true, true, "", "", "", "", "", "", "", "", null, null, "", true));
		
		$this->assertCount(1, session()->all());
		LayoutData::loadSession();
		$this->assertCount(1, session()->all());
	}
	
}