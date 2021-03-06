<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Database;

/** Class name: DatabaseTest
 * 
 * This class is the PHPUnit test for the Database model.
 * This is a unit test.
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class DatabaseTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_transaction_success
	 *
	 * This function is testing the transaction function of the Database model.
	 *
	 * @return void
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_transaction_success(){
		$permissions = [2, 3, 4];
		try{
			Database::transaction(
				function(){
					$alma = 1;
					if($alma === 1){
						$hallo = false;
					}else{
						$hallo = true;
					}
				}
			);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertTrue(true); //No exceptions, so pass it. - from PHPUnit 6, no assertion is reported as a risk
	}
	
	/** Function name: test_transaction_fail
	 *
	 * This function is testing the transaction function of the Database model.
	 *
	 * @return void
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_transaction_fail(){
		try{
			Database::transaction(
				function(){
					throw new Exception("Test exception!");
				}
			);
			$this->fail("This should throw an exception!");
		}catch(\Exception $ex){
		}
		$this->assertTrue(true); //No exceptions, so pass it. - from PHPUnit 6, no assertion is reported as a risk
	}
	
	/** Function name: test_transaction_null
	 *
	 * This function is testing the transaction function of the Database model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_transaction_null(){
		try{
			Database::transaction(null);
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertTrue(true); //No exceptions, so pass it. - from PHPUnit 6, no assertion is reported as a risk
	}

}
