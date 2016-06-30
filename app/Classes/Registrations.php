<?php

namespace App\Classes;

use DB;

class Registrations{
	protected $registrationUser;	
	
	public function __construct(){
	}
	
	public function get(){
		$ret = DB::table('users')
			->where('registered', '=', 0)
			->where('id', '!=', 0)
			->orderBy('name', 'asc')
			->get();
		return $ret == null ? [] : $ret;
	}
	
	public function getNames(){
		$ret = DB::table('users')
			->join('registrations', 'registrations.user_id', '=', 'users.id')
			->select('id', 'name', 'verified')
			->where('registered', '=', 0)
			->where('id', '!=', 0)
			->orderBy('name', 'asc')
			->get();
		return $ret == null ? [] : $ret;
	}
	
	public function getRegistrationUser(){
		return $this->registrationUser;
	}
	
	public function setRegistrationUserById($id){
		$user = DB::table('users')
			->join('registrations', 'registrations.user_id', '=', 'users.id')
			->where('registered', '=', 0)
			->where('id', '=', $id)
			->first();
		$this->registrationUser = $user == null ? [] : $user;
	}
	
}
