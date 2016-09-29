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
use App\Classes\Layout\Room;
use App\Classes\Layout\Tasks;
use App\Classes\Layout\User;

/* Class name: LayoutData
 *
 * This class handles the layout data.
 *
 * Functionality:
 * 		- stores layout classes
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
	
	/* Function name: __construct
	 * Input: -
	 * Output: -
	 *
	 * This is the constructor for the LayoutData class.
	 */
	public function __construct(){
		$this->logged = Session::has('user');
		$this->user = new User(Session::get('user') == null ? null : Session::get('user')->id);
		$this->room = new Room();
		$this->modules = new Modules();
		$this->permissions = new Permissions();
		$this->base = new BaseData();
		$this->language = Session::has('lang') ? Session::get('lang') : "hu_HU";
		$this->registrations = new Registrations();
		$this->tasks = new Tasks();
		$this->errors = new Errors();
		$this->route = $this->getRouteWithParams();
	}
	
	/* Function name: setUser
	 * Input: $user (User) - existing user
	 * Output: -
	 *
	 * This function stores the a new
	 * user as the user.
	 */
	public function setUser($user){
		$this->user = $user;
	}
	
	/* Function name: base
	 * Input: -
	 * Output: base data (BaseData)
	 *
	 * Getter function for base data.
	 */
	public function base(){
		return $this->base;
	}
	
	/* Function name: errors
	 * Input: -
	 * Output: errors (Errors)
	 *
	 * Getter function for errors.
	 */
	public function errors(){
		return $this->errors;
	}
	
	/* Function name: user
	 * Input: -
	 * Output: user (User)
	 *
	 * Getter function for user.
	 */
	public function user(){
		return $this->user;
	}
	
	/* Function name: room
	 * Input: -
	 * Output: room (Room)
	 *
	 * Getter function for rooms.
	 */
	public function room(){
		return $this->room;
	}
	
	/* Function name: tasks
	 * Input: -
	 * Output: tasks (Tasks)
	 *
	 * Getter function for tasks.
	 */
	public function tasks(){
		return $this->tasks;
	}
	
	/* Function name: logged
	 * Input: -
	 * Output: logged or not (bool)
	 *
	 * Getter function for the user's login status.
	 */
	public function logged(){
		return $this->logged;
	}
	
	/* Function name: modules
	 * Input: -
	 * Output: modules (Modules)
	 *
	 * Getter function for modules.
	 */
	public function modules(){
		return $this->modules;
	}
	
	/* Function name: permissions
	 * Input: -
	 * Output: permissions (Permissions)
	 *
	 * Getter function for permissions.
	 */
	public function permissions(){
		return $this->permissions;
	}
	
	/* Function name: registrations
	 * Input: -
	 * Output: registrations (Registrations)
	 *
	 * Getter function for registrations.
	 */
	public function registrations(){
		return $this->registrations;
	}
	
	/* Function name: lang
	 * Input: -
	 * Output: language code (text)
	 *
	 * Getter function for language code.
	 */
	public static function lang(){
		return Session::has('lang') ? Session::get('lang') : "hu_HU";
	}
	
	/* Function name: getRoute
	 * Input: -
	 * Output: route (text)
	 *
	 * Getter function for route.
	 */
	public function getRoute(){
		return $this->route;
	}
	
	/* Function name: language
	 * Input: $key (text) - 
	 * Output: text (text)
	 *
	 * This function returns the text
	 * for the requested text key.
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
	
	/* Function name: formatDate
	 * Input: $date (text) - formattable data
	 * Output: formatted date (text)
	 *
	 * This function returns the date in
	 * a custom formatted form.
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
	
	/* Function name: setLanguage
	 * Input: $language (text) - language identifier
	 * Output: -
	 *
	 * This function sets the language of
	 * the page.
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
	
	/* Function name: getRouteWithParams
	 * Input: -
	 * Output: route (text)
	 *
	 * This function returns the route for 
	 * the current page with the parameters.
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
