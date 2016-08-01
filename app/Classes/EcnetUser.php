<?php

namespace App\Classes;

use DB;
use Carbon\Carbon;

class EcnetUser extends User{
	protected $ecnetUser;
	protected $validationTime;
	protected $macAddresses;
	protected $ecnetUsers;
	protected $filterName = "";
	protected $filterUsername = "";
	
	public function __construct($id){
		$this->ecnetUser = $this->getEcnetUserData($id);
		$this->validationTime = $this->getValidationTime();
		$this->macAddresses = $this->getMACAddresses($id);
		$this->ecnetUsers = $this->getEcnetUsers();
		parent::__construct($id);
	}
	
	public function ecnetUser(){
		return $this->ecnetUser;
	}
	
	public function validationTime(){
		return $this->validationTime;
	}
	
	public function macAddresses(){
		return $this->macAddresses;
	}
	
	public function macAddressesOfUser($userId){
		return $this->getMACAddresses($userId);
	}
	
	public function hasMACSlotOrder($id){
		$order = DB::table('ecnet_mac_slot_orders')
			->where('user_id', '=', $id)
			->first();
		return ($order != null);
	}
	
	public function getNameFilter(){
		return $this->filterName;
	}
	
	public function getUsernameFilter(){
		return $this->filterUsername;
	}
	
	public function filterUsers($username, $name){
		$this->filterName = $name;
		$this->filterUsername = $username;
		$this->ecnetUsers = $this->getFilteredEcnetUsers($username, $name);
	}
	
	public function ecnetUsers($from = 0, $count = 50){
		if($this->ecnetUsers == null)
			return null;
		else if($count == 0)
			return array_slice($this->ecnetUsers, $from, count($this->ecnetUsers)-$from);
		else if($from < 0 || count($this->ecnetUsers) < $from || $count <= 0)
			return null;
		else if(count($this->ecnetUsers) < $from + $count)
			return array_slice($this->ecnetUsers, $from, count($this->ecnetUsers) - $from);
		else
			return array_slice($this->ecnetUsers, $from, $count);
	}
	
	public function addMACSlotOrder($userId, $reason){
		DB::table('ecnet_mac_slot_orders')
			->insert([
				'user_id' => $userId,
				'reason' => $reason,
				'order_time' => Carbon::now()->toDateTimeString()
			]);
	}
	
	protected function getEcnetUserData($id){
		$ret = DB::table('ecnet_user_data')->where('user_id', '=', $id)
			->first();
		return $ret == null ? [] : $ret;
	}
	
	protected function getEcnetUsers(){
		return DB::table('ecnet_user_data')
			->join('users', 'users.id', '=', 'ecnet_user_data.user_id')
			->select('users.id as id', 'users.username as username', 'users.name as name', 'ecnet_user_data.money as money', 'ecnet_user_data.valid_time as valid_time', 'ecnet_user_data.mac_slots as mac_slots')
			->get();
	}
	
	protected function getValidationTime(){
		return DB::table('ecnet_valid_date')->first();
	}
	
	protected function getMACAddresses($id){
		$addresses = DB::table('ecnet_mac_addresses')->where('user_id', '=', $id)
			->select('id', 'mac_address')
			->get();
		return $addresses === null ? [] : $addresses;
	}
	
	protected function getFilteredEcnetUsers($username, $name){
		return DB::table('ecnet_user_data')
			->join('users', 'users.id', '=', 'ecnet_user_data.user_id')
			->where('users.name', 'LIKE', '%'.$name.'%')
			->where('users.username', 'LIKE', '%'.$username.'%')
			->select('users.id as id', 'users.username as username', 'users.name as name', 'ecnet_user_data.money as money', 'ecnet_user_data.valid_time as valid_time', 'ecnet_user_data.mac_slots as mac_slots')
			->get();
	}
}
