<?php 

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\Languages;

/** Class name: LanguagesTest
 *
 * This class is the PHPUnit test for the Layout\Languages model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class LanguagesTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_getDefault
	 *
	 * This function is testing the getDefault function of the Languages model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getDefault(){
		$this->assertNotNull(Languages::getDefault());
		$this->assertNotEquals([], Languages::getDefault());
		$this->assertEquals("Hiba", Languages::getDefault()['error']);
		$this->assertEquals(Languages::hungarian(), Languages::getDefault());
	}
	
	/** Function name: test_hungarian
	 *
	 * This function is testing the hungarian function of the Languages model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_hungarian(){
		$this->assertNotNull(Languages::hungarian());
		$this->assertNotEquals([], Languages::hungarian());
		$this->assertEquals("Hiba", Languages::hungarian()['error']);
	}
	
	/** Function name: test_english
	 *
	 * This function is testing the english function of the Languages model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_english(){
		$this->assertNotNull(Languages::english());
		$this->assertNotEquals([], Languages::english());
		$this->assertEquals("Error", Languages::english()['error']);
	}
	
}