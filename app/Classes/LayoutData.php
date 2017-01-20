<?php

namespace App\Classes;

use Illuminate\Support\Facades\Session;
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

/** Class name: LayoutData
 *
 * This class handles the layout data.
 *
 * Functionality:
 * 		- stores layout classes
 * 
 * Functions that can throw exceptions:
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
	private $language;
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
		$this->logged = Session::has('user');
		$this->user = new User(Session::get('user') === null ? null : Session::get('user')->id());
		$this->room = new Rooms();
		$this->modules = new Modules();
		$this->permissions = new Permissions();
		$this->base = new BaseData();
		$this->language = Session::has('lang') ? Session::get('lang') : "hu_HU";
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
		return Session::has('lang') ? Session::get('lang') : "hu_HU";
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
	public function language($key){
		if($this->language === 'hu_HU'){
			$lang =  Languages::hungarian();
		}else if($this->language == 'en_US'){
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
	 * @return text - formatted date
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function formatDate($date){
		if($this->language === 'hu_HU'){
			return str_replace("-", ". ", str_replace(" ", ". ", $date));
		}else if($this->language === 'en_US'){
			return str_replace("-", ". ", str_replace(" ", ". ", $date));
		}else{
			return str_replace("-", ". ", str_replace(" ", ". ", $date));
		}
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
			if(Session::has('lang')){
				Session::forget('lang');
			}
			Session::put('lang', $language);
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
			$route = Route::getCurrentRoute()->getPath();
			foreach($params as $key => $value){
				$route = str_replace('{'.$key.'}', $value, $route);
			}
		}
		return $route;
	}
	
}
