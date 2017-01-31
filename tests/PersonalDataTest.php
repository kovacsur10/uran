<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\PersonalData;
use App\Classes\Data\Faculty;
use App\Classes\Data\Workshop;

/** Class name: PersonalDataTest
 *
 * This class is the PHPUnit test for the Data\PersonalData data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class PersonalDataTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_personal_data
	 *
	 * This function is testing the PersonalData data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_personal_data(){
		$user = new PersonalData("hq574o", "Budapest", "1992-02-20", "anyuka", "HS", "2012", "2015", new Faculty(1, "asd"), new Workshop(2, "qwe"));
		$this->assertEquals("hq574o", $user->neptun());
		$this->assertEquals("Budapest", $user->cityOfBirth());
		$this->assertEquals("1992-02-20", $user->dateOfBirth());
		$this->assertEquals("anyuka", $user->nameOfMother());
		$this->assertEquals("HS", $user->highSchool());
		$this->assertEquals("2012", $user->leavingExamYear());
		$this->assertEquals("2015", $user->admissionYear());
		$this->assertEquals(new Faculty(1, "asd"), $user->faculty());
		$this->assertEquals(new Workshop(2, "qwe"), $user->workshop());
	}

	/** Function name: test_personal_data_attr
	 *
	 * This function is testing the PersonalData data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_personal_data_attr(){
		$this->assertClassHasAttribute('city_of_birth', PersonalData::class);
		$this->assertClassHasAttribute('date_of_birth', PersonalData::class);
		$this->assertClassHasAttribute('name_of_mother', PersonalData::class);
		$this->assertClassHasAttribute('high_school', PersonalData::class);
		$this->assertClassHasAttribute('year_of_leaving_exam', PersonalData::class);
		$this->assertClassHasAttribute('neptun', PersonalData::class);
		$this->assertClassHasAttribute('admission_year', PersonalData::class);
		$this->assertClassHasAttribute('faculty', PersonalData::class);
		$this->assertClassHasAttribute('workshop', PersonalData::class);
	}
}