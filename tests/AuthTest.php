<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Auth;
use Illuminate\Support\Facades\Session;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\UserNotFoundException;

/** Class name: AuthTest
 *
 * This class is the PHPUnit test for the Auth model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class AuthTest extends TestCase
{
	use DatabaseTransactions;
	
	/** Function name: test_logout
	 * 
	 * This function is testing the logout function of the Auth model.
	 * 
	 * With a successful logout, the session has to be cleaned.
	 *
	 * @return void
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_logout(){
		Session::put('user', 'almafa');
		$this->assertTrue(Session::has('user'), "Session variable 'user' is not set!");
		Auth::logout();
		$this->assertFalse(Session::has('user'), "Session variable 'user' is set!");
		Auth::logout();
		$this->assertFalse(Session::has('user'), "Session variable 'user' is set!");
		
		//null value test
		Session::put('user', null);
		$this->assertFalse(Session::has('user'), "Session variable 'user' is not set!");
	}
	
	/** Function name: test_login_success
	 *
	 * This function is testing the login function of the Auth model.
	 *
	 * With a successful login, the session has to be set properly.
	 *
	 * @return void
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_login_success(){
		$this->assertFalse(Session::has('user'), "Session variable 'user' is set!");
		try{
			Auth::login('forUnitTest','forUnittest');
		}catch(\Exception $ex){
			$this->fail("Login exception: ".$ex->getMessage());
		}
		$this->assertTrue(Session::has('user'), "Session variable 'user' is not set!");
		
		//cleanup
		Session::forget('user');
	}
	
	/** Function name: test_login_failUsername
	 *
	 * This function is testing the login function of the Auth model.
	 *
	 * An invalid username should be indicated with a UserNotFoundException.
	 *
	 * @return void
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_login_failUsername(){
		$this->assertFalse(Session::has('user'), "Session variable 'user' is set!");
		try{
			Auth::login('invalidUser','invalid_password');
			$this->fail("Exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$this->assertFalse(Session::has('user'), "Session variable 'user' is set!");
	
		//cleanup
		Session::forget('user');
	}
	
	/** Function name: test_login_failPassword
	 *
	 * This function is testing the login function of the Auth model.
	 *
	 * If the password doesn't match to the username,
	 * the model should throw a ValueMismatchException.
	 *
	 * @return void
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_login_failPassword(){
		$this->assertFalse(Session::has('user'), "Session variable 'user' is set!");
		try{
			Auth::login('forUnitTest','invalid_password');
			$this->fail("Exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$this->assertFalse(Session::has('user'), "Session variable 'user' is set!");
	
		//cleanup
		Session::forget('user');
	}
	
	/** Function name: test_updatePassword_success
	 *
	 * This function is testing the updatePassword function of the Auth model.
	 *
	 * Updating the password with valid data should work well.
	 *
	 * @return void
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_updatePassword_success(){
		try{
			Auth::updatePassword('forUnitTest', 'newPassword');
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		//test new password
		try{
			Auth::login('forUnitTest','newPassword');
		}catch(\Exception $ex){
			$this->fail("Password was not updated, login exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_updatePassword_fail
	 *
	 * This function is testing the updatePassword function of the Auth model.
	 *
	 * Updating the password of an invalid user, should not work and
	 * it should be indicated by a UserNotFoundException.
	 *
	 * @return void
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_updatePassword_fail(){
		try{
			Auth::updatePassword('invalidUser', 'randomPassword');
			$this->fail("Exception was expected!");
		}catch(UserNotFoundException $ex){	
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Auth::updatePassword(null, 'randomPassword');
			$this->fail("Exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_updatePassword_null
	 *
	 * This function is testing the updatePassword function of the Auth model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_updatePassword_null(){
		try{
			Auth::updatePassword('forUnitTest', null);
			$this->fail("An exception should be thrown!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	}
	
	/** Function name: test_updatePassword_empty
	 *
	 * This function is testing the updatePassword function of the Auth model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_updatePassword_empty(){
		try{
			Auth::updatePassword('forUnitTest', '');
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		//test new password
		try{
			Auth::login('forUnitTest','');
		}catch(\Exception $ex){
			$this->fail("Password was not updated, login exception: ".$ex->getMessage());
		}
	}
}
