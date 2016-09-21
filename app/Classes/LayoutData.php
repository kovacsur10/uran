<?php

namespace App\Classes;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use App\Classes\Layout\BaseData;
use App\Classes\Layout\EcnetUser;
use App\Classes\Layout\Errors;
use App\Classes\Layout\Languages;
use App\Classes\Layout\Modules;
use App\Classes\Layout\Permissions;
use App\Classes\Layout\Registrations;
use App\Classes\Layout\Room;
use App\Classes\Layout\Tasks;
use App\Classes\Layout\User;
use DB;

class LayoutData{
	private $user;
	private $room;
	private $logged;
	private $modules;
	private $permissions;
	private $language;
	private $base;
	private $registrations;
	private $tasks;
	private $errors;
	private $route;
	
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
	
	public function errors(){
		return $this->errors;
	}
	
	public function setUser($user){
		$this->user = $user;
	}
	
	public function base(){
		return $this->base;
	}
	
	public function user(){
		return $this->user;
	}
	
	public function room(){
		return $this->room;
	}
	
	public function tasks(){
		return $this->tasks;
	}
	
	public function logged(){
		return $this->logged;
	}
	
	public function modules(){
		return $this->modules;
	}
	
	public function permissions(){
		return $this->permissions;
	}
	
	public function registrations(){
		return $this->registrations;
	}
	
	public static function lang(){
		return Session::has('lang') ? Session::get('lang') : "hu_HU";
	}
	
	public function getRoute(){
		return $this->route;
	}
	
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
	
	public function formatDate($date){
		if($this->language === 'hu_HU'){
			return str_replace("-", ". ", str_replace(" ", ". ", $date));
		}else if($this->language === 'en_US'){
			return str_replace("-", ". ", str_replace(" ", ". ", $date));
		}else{
			return str_replace("-", ". ", str_replace(" ", ". ", $date));
		}
	}
	
	public static function setLanguage($language){
		if(Session::has('lang')){
			Session::forget('lang');
		}
		Session::put('lang', $language);
	}
	
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
