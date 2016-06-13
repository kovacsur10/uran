<?php

namespace App\Classes;

use Illuminate\Support\Facades\Session;
use App\Classes\User;
use DB;

class LayoutData{
	protected $user;
	protected $logged;
	
	public function __construct(){
		$this->logged = Session::has('user');
		$this->user = new User(Session::get('user') == null ? null : Session::get('user')->id);
	}
	
	public function setUser($user){
		$this->user = $user;
	}
	
	public function user(){
		return $this->user;
	}
	
	public function logged(){
		return $this->logged;
	}
}
