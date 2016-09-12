<?php

namespace App\Classes\Layout;

use DB;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class EcnetUser extends User{

// PUBLIC VARIABLES

// PRIVATE VARIABLES

	private $ecnetUser;
	private $validationTime;
	private $macAddresses;
	private $ecnetUsers;
	private $filterName = "";
	private $filterUsername = "";
	
// PUBLIC FUNCTIONS

	public function __construct($userId){
		$this->ecnetUser = $this->getEcnetUserData($userId);
		$this->validationTime = $this->getValidationTime();
		$this->macAddresses = $this->getMACAddresses($userId);
		$this->ecnetUsers = $this->getEcnetUsers();
		parent::__construct($userId);
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
		if($this->ecnetUsers == null){
			return null;
		}else if($count == 0){
			return array_slice($this->ecnetUsers, $from, count($this->ecnetUsers)-$from);
		}else if($from < 0 || count($this->ecnetUsers) < $from || $count <= 0){
			return null;
		}else if(count($this->ecnetUsers) < $from + $count){
			return array_slice($this->ecnetUsers, $from, count($this->ecnetUsers) - $from);
		}else{
			return array_slice($this->ecnetUsers, $from, $count);
		}
	}
	
	public function addMACSlotOrder($userId, $reason){
		DB::table('ecnet_mac_slot_orders')
			->insert([
				'user_id' => $userId,
				'reason' => $reason,
				'order_time' => Carbon::now()->toDateTimeString()
			]);
	}
	
	public function register($userId){
		DB::table('ecnet_user_data')->insert([
				'user_id' => $userId,
				'valid_time' => Carbon::now()->toDateTimeString(),
			]);
	}
	
	//PrintingController
	public function getMoneyByUserId($userId){
		return DB::table('ecnet_user_data')
			->where('user_id', '=', $userId)
			->select('money')
			->first();
	}
	
	public function setMoneyForUser($userId, $money){
		DB::table('ecnet_user_data')
			->where('user_id', '=', $userId)
			->update(['money' => $money]);
	}
	
	//AccessController
	public function changeDefaultValidDate($newTime){
		try{
			DB::table('ecnet_valid_date')
				->delete();
		}catch(\Illuminate\Database\QueryException $e){
			//nothing to do, there were no lines
		}
		DB::table('ecnet_valid_date')
			->insert(['valid_date' => $newTime]);
	}
	
	public function activateUserNet($userId, $newTime){
		DB::table('ecnet_user_data')
			->where('user_id', '=', $userId)
			->update([
				'valid_time' => $newTime
			]);
	}
	
	public function macAddressExists($macAddress){
		$ret = DB::table('ecnet_mac_addresses')
			->where('mac_address', 'LIKE', $macAddress)
			->first();
		return $ret !== null;
	}
	
	public function deleteMacAddress($macAddress){
		DB::table('ecnet_mac_addresses')
			->where('mac_address', 'LIKE', $macAddress)
			->delete();
	}
	
	public function insertMacAddress($userId, $macAddress){
		DB::table('ecnet_mac_addresses')
			->insert([
				'user_id' => $userId,
				'mac_address' => $macAddress
			]);
	}
	
	//SlotController
	public function getMacSlotOrders(){
		$ret = DB::table('ecnet_mac_slot_orders')
			->join('users', 'users.id', '=', 'ecnet_mac_slot_orders.user_id')
			->select('ecnet_mac_slot_orders.id', 'users.username', 'ecnet_mac_slot_orders.reason', 'ecnet_mac_slot_orders.order_time')
			->get();
		return $ret === null ? [] : $ret;
	}
	
	public function getMacSlotOrderById($orderId){
		return DB::table('ecnet_user_data')
			->join('ecnet_mac_slot_orders', 'ecnet_mac_slot_orders.user_id', '=', 'ecnet_user_data.user_id')
			->where('ecnet_mac_slot_orders.id', '=', $orderId)
			->first();
	}

	public function setMacSlotCountForUser($userId, $macSlotCount){
		DB::table('ecnet_user_data')
			->where('user_id', '=', $userId)
			->update([
				'mac_slots' => $macSlotCount
			]);		
	}

	public function deleteMacSlotOrderById($orderId){
		DB::table('ecnet_mac_slot_orders')
			->where('id', '=', $orderId)
			->delete();
	}
	
// PRIVATE FUNCTIONS
	
	private function getEcnetUserData($id){
		$ret = DB::table('ecnet_user_data')->where('user_id', '=', $id)
			->first();
		return $ret == null ? [] : $ret;
	}
	
	private function getEcnetUsers(){
		return DB::table('ecnet_user_data')
			->join('users', 'users.id', '=', 'ecnet_user_data.user_id')
			->select('users.id as id', 'users.username as username', 'users.name as name', 'ecnet_user_data.money as money', 'ecnet_user_data.valid_time as valid_time', 'ecnet_user_data.mac_slots as mac_slots')
			->get();
	}
	
	private function getValidationTime(){
		return DB::table('ecnet_valid_date')->first();
	}
	
	private function getMACAddresses($id){
		$addresses = DB::table('ecnet_mac_addresses')->where('user_id', '=', $id)
			->select('id', 'mac_address')
			->get();
		return $addresses === null ? [] : $addresses;
	}
	
	private function getFilteredEcnetUsers($username, $name){
		return DB::table('ecnet_user_data')
			->join('users', 'users.id', '=', 'ecnet_user_data.user_id')
			->where('users.name', 'LIKE', '%'.$name.'%')
			->where('users.username', 'LIKE', '%'.$username.'%')
			->select('users.id as id', 'users.username as username', 'users.name as name', 'ecnet_user_data.money as money', 'ecnet_user_data.valid_time as valid_time', 'ecnet_user_data.mac_slots as mac_slots')
			->get();
	}
}
