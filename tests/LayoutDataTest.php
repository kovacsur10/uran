<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Session;
use App\Classes\LayoutData;

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
		$this->assertEquals($layout->formatDate('2016-12-05 06:42:52'), '2016. 12. 05. 06:42:52');
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
		LayoutData::setLanguage('en_US');
		$this->assertEquals(LayoutData::lang(), 'en_US');
	}
	
}