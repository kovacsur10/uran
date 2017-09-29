<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Classes\Data\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Auth;
use Illuminate\Contracts\Session\Session;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\UserNotFoundException;
use Carbon\Carbon;
use App\Classes\Data\StatusCode;

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
	
	/** Function name: test_isLoggedIn
	 *
	 * This function is testing the isLoggedIn function of the Auth model.
	 * 
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_isLoggedIn(){
		session()->flush();
		$this->assertFalse(session()->has('user'));
		$this->assertFalse(Auth::isLoggedIn());
		$this->assertFalse(session()->has('user'));
		
		session()->flush();
		session()->put('user', null);
		$this->assertFalse(session()->has('user'));
		$this->assertFalse(Auth::isLoggedIn());
		$this->assertFalse(session()->has('user'));
		
		session()->flush();
		session()->put('user', 'dummy');
		$this->assertTrue(session()->has('user'));
		$this->assertTrue(Auth::isLoggedIn());
		$this->assertTrue(session()->has('user'));
	}
	
	/** Function name: test_user
	 *
	 * This function is testing the user function of the Auth model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_user(){
		session()->flush();
		$this->assertFalse(session()->has('user'));
		$this->assertNull(Auth::user());
		$this->assertFalse(session()->has('user'));
		
		session()->flush();
		session()->put('user', null);
		$this->assertFalse(session()->has('user'));
		$this->assertNull(Auth::user());
		$this->assertFalse(session()->has('user'));
		
		session()->flush();
		session()->put('user', 'dummy');
		$this->assertTrue(session()->has('user'));
		$this->assertEquals('dummy', Auth::user());
		$this->assertTrue(session()->has('user'));
	}
	
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
		$user = new User(41, "", "", "", "", "", new StatusCode(1, ""), "", true, true, "", "", "", "", "", "", "", "", null, null, "", true);
		session()->put('user', $user);
		session()->put('lang', 'hu_HU');
		$this->assertTrue(session()->has('user'), "Session variable 'user' is not set!");
		$this->assertTrue(session()->has('lang'));
		Auth::logout();
		$this->assertFalse(session()->has('user'), "Session variable 'user' is set!");
		$this->assertTrue(session()->has('lang'));
		Auth::logout();
		$this->assertFalse(session()->has('user'), "Session variable 'user' is set!");
		$this->assertTrue(session()->has('lang'));
		
		//null value test
		session()->put('user', null);
		$this->assertFalse(session()->has('user'), "Session variable 'user' is not set!");
		$this->assertTrue(session()->has('lang'));
		Auth::logout();
		$this->assertFalse(session()->has('user'), "Session variable 'user' is not set!");
		$this->assertTrue(session()->has('lang'));
		
		session()->flush();
		session()->put('user', $user);
		$this->assertTrue(session()->has('user'), "Session variable 'user' is not set!");
		$this->assertFalse(session()->has('lang'));
		Auth::logout();
		$this->assertFalse(session()->has('user'), "Session variable 'user' is not set!");
		$this->assertTrue(session()->has('lang'));
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
		session()->flush();
		$this->assertFalse(session()->has('user'), "Session variable 'user' is set!");
		$this->assertCount(0, session()->all());
		try{
			Auth::login('forUnitTest','forUnittest');
		}catch(\Exception $ex){
			$this->fail("Login exception: ".$ex->getMessage());
		}
		$this->assertTrue(session()->has('user'), "Session variable 'user' is not set!");
		$this->assertCount(4, session()->all());
		
		//cleanup
		session()->forget('user');
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
		$this->assertFalse(session()->has('user'), "Session variable 'user' is set!");
		try{
			Auth::login('invalidUser','invalid_password');
			$this->fail("Exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$this->assertFalse(session()->has('user'), "Session variable 'user' is set!");
	
		//cleanup
		session()->forget('user');
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
		$this->assertFalse(session()->has('user'), "Session variable 'user' is set!");
		try{
			Auth::login('forUnitTest','invalid_password');
			$this->fail("Exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$this->assertFalse(session()->has('user'), "Session variable 'user' is set!");
	
		//cleanup
		session()->forget('user');
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
		$this->assertTrue(true); //No exceptions, so pass it. - from PHPUnit 6, no assertion is reported as a risk
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
		$this->assertTrue(true); //No exceptions, so pass it. - from PHPUnit 6, no assertion is reported as a risk
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
		$this->assertTrue(true); //No exceptions, so pass it. - from PHPUnit 6, no assertion is reported as a risk
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
		$this->assertTrue(true); //No exceptions, so pass it. - from PHPUnit 6, no assertion is reported as a risk
	}
	
	/** Function name: test_resetPassword
	 *
	 * This function is testing the resetPassword function of the Auth model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_resetPassword(){
		try{
			Auth::resetPassword(null);
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Auth::resetPassword('no_player_like_this');
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Auth::resetPassword('kovacsur10');
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertTrue(true); //No exceptions, so pass it. - from PHPUnit 6, no assertion is reported as a risk
	}
	
	/** Function name: test_endPasswordReset
	 *
	 * This function is testing the endPasswordReset function of the Auth model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function test_endPasswordReset(){
		try{
			Auth::endPasswordReset(null, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Auth::endPasswordReset("alma", null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			Auth::endPasswordReset(null, "alma");
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	
		try{
			Auth::endPasswordReset('no_player_like_this', "alma");
			$this->fail("An exception was expected!");
		}catch(UserNotFoundException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	
		try{
			$this->assertFalse(Auth::endPasswordReset('kovacsur10', "code"));
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		
		try{
			Auth::resetPassword('kovacsur10');
			$day = Carbon::now()->dayOfYear;
			$code = sha1("kovacsur102016-06-29 13:59:28Kovács Máté".$day);
			$this->assertTrue(Auth::endPasswordReset('kovacsur10', $code));
		}catch(\Exception $ex){
			$this->fail("Unexpected exception: ".$ex->getMessage());
		}
		$this->assertTrue(true); //No exceptions, so pass it. - from PHPUnit 6, no assertion is reported as a risk
	}
}
