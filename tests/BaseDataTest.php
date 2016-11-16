<?php 

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\BaseData;
use App\Classes\Data\Faculty;
use App\Classes\Data\Workshop;
use App\Classes\Data\StatusCode;
use App\Classes\Data\Country;

/** Class name: BaseDataTest
 *
 * This class is the PHPUnit test for the Layout\BaseData model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class BaseDataTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_faculties
	 *
	 * This function is testing the faculties function of the BaseData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_faculties(){
		$faculties = BaseData::faculties();
		$this->assertCount(8, $faculties);
		foreach($faculties as $faculty){
			$this->assertInstanceOf(Faculty::class, $faculty);
		}
	}
	
	/** Function name: test_faculties
	 *
	 * This function is testing the workshops function of the BaseData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_workshops(){
		$workshops = BaseData::workshops();
		$this->assertCount(17, $workshops);
		foreach($workshops as $workshop){
			$this->assertInstanceOf(Workshop::class, $workshop);
		}
	}
	
	/** Function name: test_countryCodes
	 *
	 * This function is testing the countryCodes function of the BaseData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_countryCodes(){
		$workshops = BaseData::countryCodes();
		$this->assertCount(249, $workshops);
	}
	
	/** Function name: test_admissionYears
	 *
	 * This function is testing the admissionYears function of the BaseData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_admissionYears(){
		$admissionYears = BaseData::admissionYears();
		$this->assertCount(12, $admissionYears);
	}
	
	/** Function name: test_statusCodes
	 *
	 * This function is testing the statusCodes function of the BaseData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_statusCodes(){
		$statusCodes = BaseData::statusCodes();
		$this->assertCount(8, $statusCodes);
		foreach($statusCodes as $statusCode){
			$this->assertInstanceOf(StatusCode::class, $statusCode);
		}
	}
	
	/** Function name: test_countries
	 *
	 * This function is testing the countries function of the BaseData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_countries(){
		$countries = BaseData::countries();
		$this->assertCount(249, $countries);
		foreach($countries as $country){
			$this->assertInstanceOf(Country::class, $country);
		}
	}
	
	/** Function name: test_getPagination
	 *
	 * This function is testing the getPagination function of the BaseData model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getPagination(){
		//error test cases
		$this->assertEquals([], BaseData::getPagination(null,null,null,null));
		$this->assertEquals([], BaseData::getPagination(-1,5,5,5));
		$this->assertEquals([], BaseData::getPagination(5,0,5,5));
		$this->assertEquals([], BaseData::getPagination(5,5,0,5));
		$this->assertEquals([], BaseData::getPagination(5,5,5,0));
		
		//valid test cases
		$this->assertEquals([-4 => 'disabled', -3 => 'disabled', -2 => 'disabled', -1 => 'disabled', 'disabled', 'middle', 2, 4, 'disabled', 'disabled', 'disabled'], BaseData::getPagination(0,2,5));
		$this->assertEquals([-4 => 'disabled', -3 => 'disabled', -2 => 'disabled', -1 => 'disabled', 'disabled', 'middle', 2, 4, 'disabled', 'disabled', 'disabled'], BaseData::getPagination(1,2,5));
		$this->assertEquals([-3 => 'disabled', -2 => 'disabled', -1 => 'disabled', 'disabled', 0, 'middle', 4, 'disabled', 'disabled', 'disabled', 'disabled'], BaseData::getPagination(2,2,5));
		$this->assertEquals([-3 => 'disabled', -2 => 'disabled', -1 => 'disabled', 'disabled', 0, 'middle', 4, 'disabled', 'disabled', 'disabled', 'disabled'], BaseData::getPagination(3,2,5));
		
		$this->assertEquals([1 => 0, 2 => 'middle', 3 => 4], BaseData::getPagination(3,2,5,1));
		
		$this->assertEquals([5 => 80, 100, 120, 'middle', 'disabled', 'disabled', 'disabled'], BaseData::getPagination(400,20,150,3));
		$this->assertEquals([5 => 80, 100, 120, 'middle', 'disabled', 'disabled', 'disabled'], BaseData::getPagination(150,20,150,3));
	}
}