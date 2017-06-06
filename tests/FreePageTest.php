<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\FreePage;

/** Class name: FreePageTest
 *
 * This class is the PHPUnit test for the Data\FreePage data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class FreePageTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_freePage
	 *
	 * This function is testing the FreePage data structer.
	 * 
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_freePage(){
		$faculty = new FreePage(1, "1994-05-27");
		$this->assertEquals(1, $faculty->count());
		$this->assertEquals("1994-05-27", $faculty->until());
		
		$faculty = new FreePage("1", "1994-05-27 05:10:15");
		$this->assertEquals(1, $faculty->count());
		$this->assertEquals("1994-05-27 05:10:15", $faculty->until());
	}
	
	/** Function name: test_freePage_attr
	 *
	 * This function is testing the FreePage data structer.
	 *
	 * It is used for testing the attributes of the class.
	 * 
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_freePage_attr(){
		$this->assertClassHasAttribute('count', FreePage::class);
		$this->assertClassHasAttribute('until', FreePage::class);
	}
}