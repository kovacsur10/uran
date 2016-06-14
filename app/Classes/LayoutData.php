<?php

namespace App\Classes;

use Illuminate\Support\Facades\Session;
use App\Classes\User;
use App\Classes\Room;
use DB;

class LayoutData{
	protected $user;
	protected $room;
	protected $logged;
	
	public function __construct(){
		$this->logged = Session::has('user');
		$this->user = new User(Session::get('user') == null ? null : Session::get('user')->id);
		$this->room = new Room();
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
}
