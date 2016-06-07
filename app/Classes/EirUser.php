<?php

namespace App\Classes;

use DB;

class EirUser extends User{
	protected $eirUser;
	protected $validationTime;
	protected $macAddresses;
	protected $eirUsers;
	
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
	
=======
>>>>>>> 5ea7d77... Eir user administration was added.
	public function eirUsers(){
		return $this->eirUsers;
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
}
