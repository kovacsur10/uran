<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Auth;
use Illuminate\Support\Facades\Session;
use App\Exceptions;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\UserNotFoundException;

class AuthTest extends TestCase
{
	/** Function name: test_logout
	 * 
	 * This function is testing the logout function of the Auth model.
	 * 
	 * With a successful logout, the session has to be cleaned.
	 *
	 * @return void
	 */
	public function test_logout(){
		Session::put('user', 'almafa');
		Auth::logout();
		$this->assertFalse(Session::has('user'), "Session variable 'user' is set!");
	}
	
	/** Function name: test_login_success
	 *
	 * This function is testing the login function of the Auth model.
	 *
	 * With a successful login, the session has to be set properly.
	 *
	 * @return void
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
	 * With a successful login, the session has to be set properly.
	 *
	 * @return void
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
	 * With a successful login, the session has to be set properly.
	 *
	 * @return void
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
}
