<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Session;

class AuthTest extends TestCase
{
	/** Function name: unitTest_logout
	 * 
	 * This function is testing the logout function of the Auth model.
	 * 
	 * With a successful logout, the session has to be cleaned.
	 *
	 * @return void
	 */
	public function unitTest_logout(){
		Session::put('user', 'almafa');
		Auth::logout();
		$this->assertFalse(Session::has('user'), "Session variable 'user' is set!");
	}
	
	/** Function name: unitTest_login
	 *
	 * This function is testing the login function of the Auth model.
	 *
	 * With a successful login, the session has to be set properly.
	 *
	 * @return void
	 */
	public function unitTest_login(){
		$this->assertFalse(Session::has('user'), "Session variable 'user' is set!");
		try{
			Auth::login('unittestUser','unittestUser');
		}catch(\Exception $ex){
			$this->fail("Login exception: ".$ex->message);
		}
		$this->assertTrue(Session::has('user'), "Session variable 'user' is not set!");
		
		//cleanup
		Session::forget('user');
	}
}
