<?php

namespace App\Classes;

use Illuminate\Support\Facades\Session;
use App\Classes\User;
use App\Classes\Room;
use App\Classes\Modules;
use DB;

class LayoutData{
	protected $user;
	protected $room;
	protected $logged;
	protected $modules;
	
	public function __construct(){
		$this->logged = Session::has('user');
		$this->user = new User(Session::get('user') == null ? null : Session::get('user')->id);
		$this->room = new Room();
		$this->modules = new Modules();
	}
	
	public function setUser($user){
		$this->user = $user;
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
	
}
