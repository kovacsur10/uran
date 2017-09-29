<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\Faculty;

/** Class name: FacultyTest
 *
 * This class is the PHPUnit test for the Data\Faculty data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class FacultyTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_faculty
	 *
	 * This function is testing the Faculty data structer.
	 * 
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_faculty(){
		$faculty = new Faculty(1, "alma");
		$this->assertEquals(1, $faculty->id());
		$this->assertEquals("alma", $faculty->name());
		
		$faculty = new Faculty("1", 2);
		$this->assertEquals(1, $faculty->id());
		$this->assertEquals("2", $faculty->name());
	}
	
	/** Function name: test_faculty_attr
	 *
	 * This function is testing the Faculty data structer.
	 *
	 * It is used for testing the attributes of the class.
	 * 
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_faculty_attr(){
		$this->assertClassHasAttribute('id', Faculty::class);
		$this->assertClassHasAttribute('name', Faculty::class);
	}
}