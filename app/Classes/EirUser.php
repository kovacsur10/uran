<?php

namespace App\Classes;

use DB;

class EirUser extends User{
	protected $eirUser;
	protected $validationTime;
	protected $macAddresses;
	protected $eirUsers;
	protected $filterName = "";
	protected $filterUsername = "";
	
	public function __construct($id){
		$this->eirUser = $this->getEirUserData($id);
		$this->validationTime = $this->getValidationTime();
		$this->macAddresses = $this->getMACAddresses($id);
		$this->eirUsers = $this->getEirUsers();
		parent::__construct($id);
	}
	
	public function eirUser(){
		return $this->eirUser;
	}
	
	public function validationTime(){
		return $this->validationTime;
	}
	
	public function macAddresses(){
		return $this->macAddresses;
	}
	
<<<<<<< HEAD
	public function hasMACSlotOrder($id){
		$order = DB::table('eir_mac_slot_orders')
			->where('user_id', '=', $id)
			->first();
		return ($order != null);
	}
	
<<<<<<< HEAD
=======
>>>>>>> 5ea7d77... Eir user administration was added.
	public function eirUsers(){
		return $this->eirUsers;
=======
	public function getNameFilter(){
		return $this->filterName;
	}
	
	public function getUsernameFilter(){
		return $this->filterUsername;
	}
	
	public function filterUsers($username, $name){
		$this->filterName = $name;
		$this->filterUsername = $username;
		$this->eirUsers = $this->getFilteredEirUsers($username, $name);
	}
	
	public function eirUsers($from = 0, $count = 50){
		if($this->eirUsers == null)
			return null;
		else if($count == 0)
			return array_slice($this->eirUsers, $from, count($this->eirUsers)-$from);
		else if($from < 0 || count($this->eirUsers) < $from || $count <= 0)
			return null;
		else if(count($this->eirUsers) < $from + $count)
			return array_slice($this->eirUsers, $from, count($this->eirUsers) - $from);
		else
			return array_slice($this->eirUsers, $from, $count);
>>>>>>> 39d2186... ECNET userhandling: filter is working.
	}
	
	protected function getEirUserData($id){
		return DB::table('eir_user_data')->where('user_id', '=', $id)
										 ->first();
	}
	
	protected function getEirUsers(){
		return DB::table('eir_user_data')
			->join('users', 'users.id', '=', 'eir_user_data.user_id')
			->select('users.id as id', 'users.username as username', 'users.name as name', 'eir_user_data.money as money', 'eir_user_data.valid_time as valid_time', 'eir_user_data.mac_slots as mac_slots')
			->get();
	}
	
	protected function getValidationTime(){
		return DB::table('eir_valid_date')->first();
	}
	
	protected function getMACAddresses($id){
		return DB::table('eir_mac_addresses')->where('user_id', '=', $id)
											 ->select('id', 'mac_address')
											 ->get();
	}
	
	protected function getFilteredEirUsers($username, $name){
		return DB::table('eir_user_data')
			->join('users', 'users.id', '=', 'eir_user_data.user_id')
			->where('users.name', 'LIKE', '%'.$name.'%')
			->where('users.username', 'LIKE', '%'.$username.'%')
			->select('users.id as id', 'users.username as username', 'users.name as name', 'eir_user_data.money as money', 'eir_user_data.valid_time as valid_time', 'eir_user_data.mac_slots as mac_slots')
			->get();
	}
}
