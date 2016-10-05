<?php

namespace App\Classes\Layout;

use DB;
use Carbon\Carbon;

/* Class name: EcnetUser
 *
 * This class handles the ECnet
 * user database and other model
 * functionalities.
 *
 * Functionality:
 * 		- internet connection
 * 		- MAC addresses
 * 		- admin filtering
 * 
 * Functions that can throw exceptions:
 * 		register
 */
class EcnetUser extends User{

// PRIVATE DATA

	private $ecnetUser;
	private $validationTime;
	private $macAddresses;
	private $ecnetUsers;
	private $filterName = "";
	private $filterUsername = "";
	
// PUBLIC FUNCTIONS

	/* Function name: __construct
	 * Input: $userId (integer) - user's identifier
	 * Output: -
	 *
	 * Constructor fot the EcnetUser class.
	 */
	public function __construct($userId){
		$this->ecnetUser = $this->getEcnetUserData($userId);
		$this->validationTime = $this->getValidationTime();
		$this->macAddresses = $this->getMACAddresses($userId);
		$this->ecnetUsers = $this->getEcnetUsers();
		parent::__construct($userId);
	}
	
	/* Function name: ecnetUser
	 * Input: -
	 * Output: User
	 *
	 * The getter function of the ECnet user.
	 */
	public function ecnetUser(){
		return $this->ecnetUser;
	}
	
	/* Function name: validationTime
	 * Input: -
	 * Output: date | NULL
	 *
	 * The getter function of the ECnet user's
	 * internet validation date.
	 */
	public function validationTime(){
		return $this->validationTime;
	}
	
	/* Function name: macAddresses
	 * Input: -
	 * Output: array of MAC addresses
	 *
	 * The getter function of the ECnet user's MAC addresses.
	 */
	public function macAddresses(){
		return $this->macAddresses;
	}
	
	/* Function name: getNameFilter
	 * Input: -
	 * Output: text | NULL
	 *
	 * The getter function of the name filter of the ECnet admin panel.
	 */
	public function getNameFilter(){
		return $this->filterName;
	}
	
	/* Function name: getUsernameFilter
	 * Input: -
	 * Output: text | NULL
	 *
	 * The getter function of the username filter of the ECnet admin panel.
	 */
	public function getUsernameFilter(){
		return $this->filterUsername;
	}
	
	/* Function name: macAddressesOfUser
	 * Input: $userId (integer) - user's identifier
	 * Output: array of MAC addresses
	 *
	 * This function returns the set MAC
	 * addresses of the requested user.
	 */
	public function macAddressesOfUser($userId){
		return $this->getMACAddresses($userId);
	}
	
	/* Function name: filterUsers
	 * Input: 	$username (text) - user's name
	 * 			$name (text) - user's username
	 * Output: -
	 *
	 * This function sets the filter fields
	 * of the ECnet admin panel.
	 */
	public function filterUsers($username, $name){
		$this->filterName = $name;
		$this->filterUsername = $username;
		$this->ecnetUsers = $this->getFilteredEcnetUsers($username, $name);
	}
	
	/* Function name: ecnetUsers
	 * Input: 	$from (integer) - first user id
	 * 			$count (integer) - visible count
	 * Output: array of users
	 *
	 * This function returns the ECnet users.
	 * Only a part of the users can be queried.
	 * The from value indicatates, that the first
	 * returned user should be the from indexed user,
	 * the maximum count of the returned user is
	 * the second parameter.
	 * 
	 * If the count equals to 0, the return count
	 * is "unlimited".
	 */
	public function ecnetUsers($from = 0, $count = 50){
		if($from < 0 || count($this->ecnetUsers) < $from || $count < 0){
			return [];
		}else if($count === 0 || count($this->ecnetUsers) < $from + $count){
			return array_slice($this->ecnetUsers, $from, count($this->ecnetUsers) - $from);
		}else{
			return array_slice($this->ecnetUsers, $from, $count);
		}
	}
	
	/* Function name: register
	 * Input: $userId (integer) - user's identifier
	 * Output: -
	 *
	 * This function creates the new
	 * user's ECnet user data.
	 * 
	 * THROWS EXCEPTIONS!
	 */
	public function register($userId){
		DB::table('ecnet_user_data')
			->insert([
				'user_id' => $userId,
				'valid_time' => Carbon::now()->toDateTimeString(),
			]);
	}
	
	//PRINTING CONTROLLER
	
	/* Function name: getMoneyByUserId
	 * Input: $userId (integer) - user's identifier
	 * Output: integer (money)
	 *
	 * This function returns the requested
	 * user's ECnet money.
	 */
	public function getMoneyByUserId($userId){
		try{
			$money = DB::table('ecnet_user_data')
				->where('user_id', '=', $userId)
				->select('money')
				->first()
				->money;
		}catch(\Exception $ex){
			$money = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_user_data' was not successful! ".$ex->getMessage());
		}
		return $money;
	}
	
	/* Function name: setMoneyForUser
	 * Input: 	$userId (integer) - user's identifier
	 * 			$money (integer) - money
	 * Output: -
	 *
	 * This function sets the requested
	 * user's ECnet money to the given
	 * value.
	 */
	public function setMoneyForUser($userId, $money){
		try{
			DB::table('ecnet_user_data')
				->where('user_id', '=', $userId)
				->update(['money' => $money]);
		}catch(\Excetion $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'ecnet_user_data' was not successful! ".$ex->getMessage());
		}
	}
	
	//ACCESS CONTROLLER
	
	/* Function name: changeDefaultValidDate
	 * Input: $newTime (datetime) - new default datetime
	 * Output: -
	 *
	 * This function sets the default
	 * valid time for ECnet internet access.
	 */
	public function changeDefaultValidDate($newTime){
		try{
			DB::table('ecnet_valid_date')
				->delete();
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'ecnet_valid_date' was not successful! ".$ex->getMessage());
		}
		try{
			DB::table('ecnet_valid_date')
				->insert(['valid_date' => $newTime]);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'ecnet_valid_date' was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: activateUserNet
	 * Input: 	$userId (integer) - user's identifier
	 * 			$newTime (datetime) - new default datetime
	 * Output: integer (error code)
	 *
	 * This function sets the internet valid
	 * time of the requested user.
	 */
	public function activateUserNet($userId, $newTime){
		$errorCode = 0;
		try{
			DB::table('ecnet_user_data')
				->where('user_id', '=', $userId)
				->update([
					'valid_time' => $newTime
				]);
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'ecnet_user_data' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
	/* Function name: macAddressExists
	 * Input: $macAddress (text) - mac address
	 * Output: bool (exists or not)
	 *
	 * This function returns whethet the
	 * requested MAC address exists in
	 * the database or not.
	 */
	public function macAddressExists($macAddress){
		try{
			$macAddress = DB::table('ecnet_mac_addresses')
				->where('mac_address', 'LIKE', $macAddress)
				->first();
		}catch(\Exception $ex){
			$macAddress = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_mac_addresses' was not successful! ".$ex->getMessage());
		}
		return $macAddress !== null;
	}
	
	/* Function name: deleteMacAddress
	 * Input: $macAddress (text) - mac address
	 * Output: -
	 *
	 * This function sets the internet valid
	 * time of the requested user.
	 */
	public function deleteMacAddress($macAddress){
		try{
			DB::table('ecnet_mac_addresses')
				->where('mac_address', 'LIKE', $macAddress)
				->delete();
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'ecnet_mac_addresses' was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: insertMacAddress
	 * Input: 	$userId (integer) - user's identifier
	 * 			$macAddress (text) - mac address
	 * Output: -
	 *
	 * This function sets the internet valid
	 * time of the requested user.
	 */
	public function insertMacAddress($userId, $macAddress){
		try{
			DB::table('ecnet_mac_addresses')
				->insert([
					'user_id' => $userId,
					'mac_address' => $macAddress
				]);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'ecnet_mac_addresses' was not successful! ".$ex->getMessage());
		}
	}
	
	//SLOT CONTROLLER
	
	/* Function name: hasMACSlotOrder
	 * Input: $userId (integer) - user's identifier
	 * Output: bool (has an order or not)
	 *
	 * This function returns a boolean value
	 * based on the requested user's MAC
	 * slot order. If the user has an active
	 * order, it returns true, otherwise false.
	 */
	public function hasMACSlotOrder($userId){
		try{
			$order = DB::table('ecnet_mac_slot_orders')
			->where('user_id', '=', $userId)
			->first();
		}catch(\Exception $ex){
			$order = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_mac_slot_orders' was not successful! ".$ex->getMessage());
		}
		return ($order !== null);
	}
	
	/* Function name: addMACSlotOrder
	 * Input: 	$userId (integer) - user's identifier
	 * 			$reason (text) - reason of the order
	 * Output: integer (error code)
	 *
	 * This function returns the existing
	 * MAC slot orders.
	 */
	public function addMACSlotOrder($userId, $reason){
		$errorCode = 0;
		try{
			DB::table('ecnet_mac_slot_orders')
				->insert([
						'user_id' => $userId,
						'reason' => $reason,
						'order_time' => Carbon::now()->toDateTimeString()
				]);
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'ecnet_mac_slot_orders' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
	/* Function name: getMacSlotOrders
	 * Input: -
	 * Output: array of MAC slot orders
	 *
	 * This function returns the existing
	 * MAC slot orders.
	 */
	public function getMacSlotOrders(){
		try{
			$orders = DB::table('ecnet_mac_slot_orders')
				->join('users', 'users.id', '=', 'ecnet_mac_slot_orders.user_id')
				->select('ecnet_mac_slot_orders.id', 'users.username', 'ecnet_mac_slot_orders.reason', 'ecnet_mac_slot_orders.order_time')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$order = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_mac_slot_orders', joined to 'users' was not successful! ".$ex->getMessage());
		}
		return $order;
	}
	
	/* Function name: setMacSlotCountForUser
	 * Input: $orderId (integer) - user's identifier
	 * Output: integer (error code)
	 *
	 * This function returns the requested
	 * MAC slot order.
	 */
	public function getMacSlotOrderById($orderId){
		try{
			$order = DB::table('ecnet_user_data')
				->join('ecnet_mac_slot_orders', 'ecnet_mac_slot_orders.user_id', '=', 'ecnet_user_data.user_id')
				->where('ecnet_mac_slot_orders.id', '=', $orderId)
				->first();
		}catch(\Exception $ex){
			$order = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_user_data', joined to 'ecnet_mac_slot_orders' was not successful! ".$ex->getMessage());
		}
		return $order;
	}

	/* Function name: setMacSlotCountForUser
	 * Input: 	$userId (integer) - user's identifier
	 * 			$macSlotCount (integer) - count of mac slots
	 * Output: integer (error code)
	 *
	 * This function modifies the MAC slot
	 * limit for the requested user.
	 */
	public function setMacSlotCountForUser($userId, $macSlotCount){
		$errorCode = 0;
		try{
			DB::table('ecnet_user_data')
				->where('user_id', '=', $userId)
				->update([
					'mac_slots' => $macSlotCount
				]);	
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'ecnet_user_data' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}

	/* Function name: deleteMacSlotOrderById
	 * Input: $orderId (integer) - identifier of an order
	 * Output: integer (error code)
	 *
	 * This function removes the requested
	 * MAC slot order.
	 */
	public function deleteMacSlotOrderById($orderId){
		$errorCode = 0;
		try{
			DB::table('ecnet_mac_slot_orders')
				->where('id', '=', $orderId)
				->delete();
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'ecnet_mac_slot_orders' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
// PRIVATE FUNCTIONS
	
	/* Function name: getEcnetUserData
	 * Input: $userId (integer) - user's identifier
	 * Output: User | NULL
	 *
	 * This function returns the requested
	 * user's data.
	 */
	private function getEcnetUserData($userId){
		try{
			$userData = DB::table('ecnet_user_data')
				->where('user_id', '=', $userId)
				->first();
		}catch(\Exception $ex){
			$userData = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_user_data' was not successful! ".$ex->getMessage());
		}
		return $userData;
	}
	
	/* Function name: getEcnetUsers
	 * Input: -
	 * Output: array of users
	 *
	 * This function returns the internet
	 * validation date for the current user
	 * if that exists.
	 */
	private function getEcnetUsers(){
		try{
			$users = DB::table('ecnet_user_data')
				->join('users', 'users.id', '=', 'ecnet_user_data.user_id')
				->select('users.id as id', 'users.username as username', 'users.name as name', 'ecnet_user_data.money as money', 'ecnet_user_data.valid_time as valid_time', 'ecnet_user_data.mac_slots as mac_slots')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_user_data', joined to 'users' was not successful! ".$ex->getMessage());
		}
		return $users;
	}
	
	/* Function name: getValidationTime
	 * Input: -
	 * Output: date | NULL
	 *
	 * This function returns the internet
	 * validation date for the current user
	 * if that exists.
	 */
	private function getValidationTime(){
		try{
			$datetime = DB::table('ecnet_valid_date')
				->first()
				->valid_date;
		}catch(\Exception $ex){
			$datetime = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_valid_date' was not successful! ".$ex->getMessage());
		}
		return $datetime;
	}
	
	/* Function name: getMACAddresses
	 * Input: $userId (integer) - user's identifier
	 * Output: array of MAC addresses
	 *
	 * This function returns the internet
	 * validation date for the current user
	 * if that exists.
	 */
	private function getMACAddresses($userId){
		try{
			$macAddresses = DB::table('ecnet_mac_addresses')
				->where('user_id', '=', $userId)
				->select('id', 'mac_address')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$macAddresses = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_mac_addresses' was not successful! ".$ex->getMessage());
		}
		return $macAddresses;
	}
	
	/* Function name: getFilteredEcnetUsers
	 * Input: 	$username (text) - user's name
	 * 			$name (text) - user's username
	 * Output: array of users
	 *
	 * This function returns the filtered
	 * ECnet users.
	 */
	private function getFilteredEcnetUsers($username, $name){
		try{
			$users = DB::table('ecnet_user_data')
				->join('users', 'users.id', '=', 'ecnet_user_data.user_id')
				->where('users.name', 'LIKE', '%'.$name.'%')
				->where('users.username', 'LIKE', '%'.$username.'%')
				->select('users.id as id', 'users.username as username', 'users.name as name', 'ecnet_user_data.money as money', 'ecnet_user_data.valid_time as valid_time', 'ecnet_user_data.mac_slots as mac_slots')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_user_data', joined to 'users' was not successful! ".$ex->getMessage());
		}
		return $users;
	}
}
