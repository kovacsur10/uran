<?php

namespace App\Classes;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Route;
use App\Classes\Layout\BaseData;
use App\Classes\Layout\Errors;
use App\Classes\Layout\Languages;
use App\Classes\Layout\Modules;
use App\Classes\Layout\Permissions;
use App\Classes\Layout\Registrations;
use App\Classes\Layout\Rooms;
use App\Classes\Layout\Tasks;
use App\Classes\Layout\User;
use App\Classes\Auth;
use App\Persistence\P_User;
use App\Classes\Layout\EcnetData;

/** Class name: LayoutData
 *
 * This class handles the layout data.
 *
 * Functionality:
 * 		- stores layout classes
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class LayoutData{
	
// PRIVATE DATA
	
	private $user;
	private $room;
	private $logged; // the user is logged or not (bool)
	private $modules;
	private $permissions;
	private $base;
	private $registrations;
	private $tasks;
	private $errors;
	private $route;
	
// PUBLIC FUNCTIONS
	
	/** Function name: __construct
	 *
	 * This is the constructor for the LayoutData class.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(){
		$this->logged = Auth::isLoggedIn();
		$this->user = new User(session()->get('user') === null ? null : session()->get('user')->id());
		$this->room = new Rooms();
		$this->modules = new Modules();
		$this->permissions = new Permissions();
		$this->base = new BaseData();		
		$this->registrations = new Registrations();
		$this->tasks = new Tasks();
		$this->errors = new Errors();
		$this->route = $this->getRouteWithParams();
	}
	
	/** Function name: setUser
	 *
	 * This function stores the a new
	 * user as the user.
	 * 
	 * @param User $user - existing user
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function setUser($user){
		$this->user = $user;
	}
	
	/** Function name: base
	 *
	 * Getter function for base data.
	 * 
	 * @return BaseData
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function base(){
		return $this->base;
	}
	
	/** Function name: errors
	 *
	 * Getter function for errors.
	 * 
	 * @return Errors
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function errors(){
		return $this->errors;
	}
	
	/** Function name: user
	 *
	 * Getter function for user.
	 * 
	 * @return User
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function user(){
		return $this->user;
	}
	
	/** Function name: room
	 *
	 * Getter function for rooms.
	 * 
	 * @return Rooms
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function room(){
		return $this->room;
	}
	
	/** Function name: tasks
	 *
	 * Getter function for tasks.
	 * 
	 * @return Tasks
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function tasks(){
		return $this->tasks;
	}
	
	/** Function name: logged
	 *
	 * Getter function for the user's login status.
	 * 
	 * @return bool - logged or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function logged(){
		return $this->logged;
	}
	
	/** Function name: modules
	 *
	 * Getter function for modules.
	 * 
	 * @return Modules
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function modules(){
		return $this->modules;
	}
	
	/** Function name: permissions
	 *
	 * Getter function for permissions.
	 * 
	 * @return Permissions
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function permissions(){
		return $this->permissions;
	}
	
	/** Function name: registrations
	 *
	 * Getter function for registrations.
	 * 
	 * @return Registrations
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function registrations(){
		return $this->registrations;
	}
	
	/** Function name: lang
	 *
	 * Getter function for language code.
	 * 
	 * @return text - language text identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function lang(){
		return \App::getLocale();
	}
	
	/** Function name: getRoute
	 *
	 * Getter function for route.
	 * 
	 * @return text - route
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getRoute(){
		return $this->route;
	}
	
	/** Function name: language
	 *
	 * This function returns the text
	 * for the requested text key.
	 * 
	 * @param text $key - word text identifier 
	 * @return text - word/phrase in the selected language
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	// @Deprecated
	public function language($key){
		if(\App::isLocale('hu')){
			$lang =  Languages::hungarian();
		}else if(\App::isLocale('en')){
			$lang =  Languages::english();
		}else{
			$lang =  Languages::getDefault();
		}
		if(array_key_exists($key, $lang)){
			return $lang[$key];
		}else{
			$lang =  Languages::getDefault();
			if(array_key_exists($key, $lang)){
				return $lang[$key];
			}else{
				return 'missing tag';
			}
		}
	}
	
	/** Function name: formatDate
	 *
	 * This function returns the date in
	 * a custom formatted form.
	 * 
	 * @param text $date - formattable data
	 * @param bool $onlyDate - only show date part, ignore time part if exists
	 * @return text - formatted date
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function formatDate($date, $onlyDate = false){
		$returnedDateTime = "";
		if(\App::isLocale('hu')){
			if(strstr($date, ". ") !== FALSE){
				$returnedDateTime = str_replace("-", ". ", $date);
			}else{
				$returnedDateTime = str_replace("-", ". ", str_replace(" ", ". ", $date));
			}
		}else if(\App::isLocale('en')){
			$returnedDateTime = str_replace("-", ". ", str_replace(" ", ". ", $date));
		}else{
			$returnedDateTime = str_replace("-", ". ", str_replace(" ", ". ", $date));
		}
		return $onlyDate ? substr($returnedDateTime, 0, 13) : $returnedDateTime;
	}
	
	/** Function name: setLanguage
	 *
	 * This function sets the language of
	 * the page.
	 * 
	 * @param text $language - language identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function setLanguage($language){
		if($language !== null){
			\App::setLocale($language);
			session()->put('locale', $language);
		}
	}
	
	/** Function name: saveSession
	 *
	 * This function saves the session data to the database.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function saveSession(){
		if(Auth::user() === null){
			return;
		}
		
		$saving = [];
		$tmp = EcnetData::getSessionData();
		$saving = array_merge($saving, $tmp);
		$tmp = Rooms::getSessionData();
		$saving = array_merge($saving, $tmp);
		$tmp = Tasks::getSessionData();
		$saving = array_merge($saving, $tmp);
		P_User::saveSession(Auth::user()->id(), $saving);
	}
	
	/** Function name: loadSession
	 *
	 * This function saves the session data to the database.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function loadSession(){
		if(Auth::user() === null){
			return;
		}
		try{
			$sessionData = P_User::loadSession(Auth::user()->id());
			foreach($sessionData as $key => $value){
				switch($key){
					case 'tasks_mytasks_filter':
						if($value === "1" || $value === "true" || $value === 1 || $value === true){
							$value = true;
						}else{
							$value = false;
						}
						break;
					case 'tasks_hide_closed_filter':
						if($value === "1" || $value === "true" || $value === 1 || $value === true){
							$value = true;
						}else{
							$value = false;
						}
						break;
				}
				session()->put($key, $value);
			}
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Could not load session data. ".$ex->getMessage());
		}
	}
	
// PRIVATE FUNCTIONS
	
	/** Function name: getRouteWithParams
	 *
	 * This function returns the route for 
	 * the current page with the parameters.
	 * 
	 * @return text - route
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getRouteWithParams(){
		$route = Route::getCurrentRoute();
		if($route !== null){
			$params = Route::getCurrentRoute()->parameters();
			$route = Route::getCurrentRoute()->uri();
			foreach($params as $key => $value){
				$route = str_replace('{'.$key.'}', $value, $route);
			}
		}
		return $route;
	}
	
}
