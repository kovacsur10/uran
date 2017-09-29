<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\Country;

/** Class name: CountryTest
 *
 * This class is the PHPUnit test for the Data\Country data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class CountryTest extends BrowserKitTestCase
{
	use DatabaseTransactions;

	/** Function name: test_country
	 *
	 * This function is testing the Country data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_country(){
		$country = new Country("al", "alma");
		$this->assertEquals("al", $country->id());
		$this->assertEquals("alma", $country->name());

		$country = new Country(1, 2);
		$this->assertEquals("1", $country->id());
		$this->assertEquals("2", $country->name());
	}

	/** Function name: test_country_attr
	 *
	 * This function is testing the Country data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_country_attr(){
		$this->assertClassHasAttribute('id', Country::class);
		$this->assertClassHasAttribute('name', Country::class);
	}
}