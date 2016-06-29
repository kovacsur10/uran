<?php

namespace App\Classes;

use Illuminate\Support\Facades\Session;
use App\Classes\User;
use App\Classes\Room;
use App\Classes\Modules;
use App\Classes\Permissions;
use App\Classes\Languages;
use DB;

class LayoutData{
	protected $user;
	protected $room;
	protected $logged;
	protected $modules;
	protected $permissions;
	protected $language;
	protected $base;
	
	public function __construct(){
		$this->logged = Session::has('user');
		$this->user = new User(Session::get('user') == null ? null : Session::get('user')->id);
		$this->room = new Room();
		$this->modules = new Modules();
		$this->permissions = new Permissions();
		$this->base = new BaseData();
		$this->language = Session::has('lang') ? Session::get('lang') : "hu_HU";
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
	
	public function logged(){
		return $this->logged;
	}
	
	public function modules(){
		return $this->modules;
	}
	
	public function permissions(){
		return $this->permissions;
	}
	
	public function lang(){
		return Session::has('lang') ? Session::get('lang') : "hu_HU";
	}
	
	public function language($key){
		if($this->language == 'hu_HU'){
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
	
}
