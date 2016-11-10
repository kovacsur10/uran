<?php

namespace App\Classes\Layout;

use Carbon\Carbon;
use App\Persistence\P_Ecnet;

/** Class name: EcnetUser
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
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
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

	/** Function name: __construct
	 *
	 * Constructor fot the EcnetUser class.
	 * 
	 * @param int $userId - user's identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct($userId){
		$this->ecnetUser = $this->getEcnetUserData($userId);
		$this->validationTime = $this->getValidationTime();
		$this->macAddresses = $this->getMACAddresses($userId);
		$this->ecnetUsers = $this->getEcnetUsers();
		parent::__construct($userId);
	}
	
	/** Function name: ecnetUser
	 *
	 * The getter function of the ECnet user.
	 * 
	 * @return EcnetUser
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function ecnetUser(){
		return $this->ecnetUser;
	}
	
	/** Function name: validationTime
	 *
	 * The getter function of the ECnet user's
	 * internet validation date.
	 * 
	 * @return datetime|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function validationTime(){
		return $this->validationTime;
	}
	
	/** Function name: macAddresses
	 *
	 * The getter function of the ECnet user's MAC addresses.
	 * 
	 * @return array of MAC addresses
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function macAddresses(){
		return $this->macAddresses;
	}
	
	/** Function name: getNameFilter
	 *
	 * The getter function of the name filter of the ECnet admin panel.
	 * 
	 * @return text|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getNameFilter(){
		return $this->filterName;
	}
	
	/** Function name: getUsernameFilter
	 *
	 * The getter function of the username filter of the ECnet admin panel.
	 * 
	 * @return text|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getUsernameFilter(){
		return $this->filterUsername;
	}
	
	/** Function name: macAddressesOfUser
	 *
	 * This function returns the set MAC
	 * addresses of the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @return array of MAC addresses
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function macAddressesOfUser($userId){
		return $this->getMACAddresses($userId);
	}
	
	/** Function name: filterUsers
	 *
	 * This function sets the filter fields
	 * of the ECnet admin panel.
	 * 
	 * @param text $username - user's username
	 * @param text $name - user's name
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function filterUsers($username, $name){
		$this->filterName = $name;
		$this->filterUsername = $username;
		$this->ecnetUsers = $this->getFilteredEcnetUsers($username, $name);
	}
	
	/** Function name: ecnetUsers
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
	 * 
	 * @param int $from - first user id
	 * @param int $count - visible count
	 * @return array of Users
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
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
	
	/** Function name: register
	 *
	 * This function creates the new
	 * user's ECnet user data.
	 * 
	 * @param int $userId - user's identifier
	 * 
	 * @exception QueryException
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function register($userId){
		P_Ecnet::addNewUser($userId, Carbon::now()->toDateTimeString());
	}
	
	//PRINTING CONTROLLER
	
	/** Function name: getMoneyByUserId
	 *
	 * This function returns the requested
	 * user's ECnet money.
	 * 
	 * @param int $userId - user's identifier
	 * @return int - money
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getMoneyByUserId($userId){
		try{
			$money = P_Ecnet::getUser($userId)->money;
		}catch(\Exception $ex){
			$money = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_user_data' was not successful! ".$ex->getMessage());
		}
		return $money;
	}
	
	/** Function name: setMoneyForUser
	 *
	 * This function sets the requested
	 * user's ECnet money to the given
	 * value.
	 * 
	 * @param int $userId - user's identifier
	 * @param int $money - money
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function setMoneyForUser($userId, $money){
		try{
			P_Ecnet::updateUser($userId, null, null, $money);
		}catch(\Excetion $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'ecnet_user_data' was not successful! ".$ex->getMessage());
		}
	}
	
	//ACCESS CONTROLLER
	
	/** Function name: changeDefaultValidDate
	 *
	 * This function sets the default
	 * valid time for ECnet internet access.
	 * 
	 * @param datetime $newTime - new default datetime
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function changeDefaultValidDate($newTime){
		try{
			P_Ecnet::updateValidDate($newTime);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
	}
	
	/** Function name: activateUserNet
	 *
	 * This function sets the internet valid
	 * time of the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @param datetime $newTime - new default datetime
	 * @return int - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function activateUserNet($userId, $newTime){
		$errorCode = 0;
		try{
			P_Ecnet::updateUser($userId, $newTime, null, null);
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'ecnet_user_data' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
	/** Function name: macAddressExists
	 *
	 * This function returns whethet the
	 * requested MAC address exists in
	 * the database or not.
	 * 
	 * @param text $macAddress - mac address
	 * @return bool - exists or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function macAddressExists($macAddress){
		try{
			$macAddress = P_Ecnet::getMacAddress($macAddress);
		}catch(\Exception $ex){
			$macAddress = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_mac_addresses' was not successful! ".$ex->getMessage());
		}
		return $macAddress !== null;
	}
	
	/** Function name: deleteMacAddress
	 *
	 * This function sets the internet valid
	 * time of the requested user.
	 * 
	 * @param text $macAddress - mac address
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function deleteMacAddress($macAddress){
		try{
			P_Ecnet::removeMacAddress($macAddress);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'ecnet_mac_addresses' was not successful! ".$ex->getMessage());
		}
	}
	
	/** Function name: insertMacAddress
	 *
	 * This function sets the internet valid
	 * time of the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @param text $macAddress - mac address
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function insertMacAddress($userId, $macAddress){
		try{
			P_Ecnet::addMacAddress($macAddress, $userId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'ecnet_mac_addresses' was not successful! ".$ex->getMessage());
		}
	}
	
	//SLOT CONTROLLER
	
	/** Function name: hasMACSlotOrder
	 *
	 * This function returns a boolean value
	 * based on the requested user's MAC
	 * slot order. If the user has an active
	 * order, it returns true, otherwise false.
	 * 
	 * @param int $userId - user's identifier
	 * @return bool - has an order or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function hasMACSlotOrder($userId){
		try{
			$order = P_Ecnet::getMacSlotOrdersForUser($userId);
		}catch(\Exception $ex){
			$order = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_mac_slot_orders' was not successful! ".$ex->getMessage());
		}
		return ($order !== []);
	}
	
	/** Function name: addMACSlotOrder
	 *
	 * This function returns the existing
	 * MAC slot orders.
	 * 
	 * @param int $userId - user's identifier
	 * @param text $reason - reason of the order
	 * @return integer - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addMACSlotOrder($userId, $reason){
		$errorCode = 0;
		try{
			P_Ecnet::addMacSlotOrder($userId, $reason, Carbon::now()->toDateTimeString());
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'ecnet_mac_slot_orders' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
	/** Function name: getMacSlotOrders
	 *
	 * This function returns the existing
	 * MAC slot orders.
	 * 
	 * @return array of MAC slot orders
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getMacSlotOrders(){
		try{
			$orders = P_Ecnet::getMacSlotOrders();
		}catch(\Exception $ex){
			$order = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_mac_slot_orders', joined to 'users' was not successful! ".$ex->getMessage());
		}
		return $order;
	}
	
	/** Function name: getMacSlotOrderById
	 *
	 * This function returns the requested
	 * MAC slot order.
	 * 
	 * @param int $orderId - user's identifier
	 * @return int - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getMacSlotOrderById($orderId){
		try{
			$order = P_Ecnet::getMacSlotOrder($orderId);
		}catch(\Exception $ex){
			$order = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_user_data', joined to 'ecnet_mac_slot_orders' was not successful! ".$ex->getMessage());
		}
		return $order;
	}

	/** Function name: setMacSlotCountForUser
	 *
	 * This function modifies the MAC slot
	 * limit for the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @param int $macSlotCount - count of mac slots
	 * @return int - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function setMacSlotCountForUser($userId, $macSlotCount){
		$errorCode = 0;
		try{
			P_Ecnet::updateUser($userId, null, $macSlotCount, null);
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'ecnet_user_data' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}

	/** Function name: deleteMacSlotOrderById
	 *
	 * This function removes the requested
	 * MAC slot order.
	 * 
	 * @param int $orderId - identifier of an order
	 * @return integer - error code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function deleteMacSlotOrderById($orderId){
		$errorCode = 0;
		try{
			P_Ecnet::removeMacSlotOrder($orderId);
		}catch(\Exception $ex){
			$errorCode = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table 'ecnet_mac_slot_orders' was not successful! ".$ex->getMessage());
		}
		return $errorCode;
	}
	
// PRIVATE FUNCTIONS
	
	/** Function name: getEcnetUserData
	 *
	 * This function returns the requested
	 * user's data.
	 * 
	 * @param int $userId - user's identifier
	 * @return ECnetUser|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getEcnetUserData($userId){
		try{
			$userData = P_Ecnet::getUser($userId);
		}catch(\Exception $ex){
			$userData = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_user_data' was not successful! ".$ex->getMessage());
		}
		return $userData;
	}
	
	/** Function name: getEcnetUsers
	 *
	 * This function returns the internet
	 * validation date for the current user
	 * if that exists.
	 * 
	 * @return array of ECnet users 
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getEcnetUsers(){
		try{
			$users = P_Ecnet::getUsers();
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_user_data', joined to 'users' was not successful! ".$ex->getMessage());
		}
		return $users;
	}
	
	/** Function name: getValidationTime
	 *
	 * This function returns the internet
	 * validation date for the current user
	 * if that exists.
	 * 
	 * @return datetime|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getValidationTime(){
		try{
			$datetime = P_Ecnet::getValidDate();
		}catch(\Exception $ex){
			$datetime = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_valid_date' was not successful! ".$ex->getMessage());
		}
		return $datetime;
	}
	
	/** Function name: getMACAddresses
	 *
	 * This function returns the internet
	 * validation date for the current user
	 * if that exists.
	 * 
	 * @param int $userId - user's identifier
	 * @return array of MAC addresses
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getMACAddresses($userId){
		try{
			$macAddresses = P_Ecnet::getMacAddressesForUser($userId);
		}catch(\Exception $ex){
			$macAddresses = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_mac_addresses' was not successful! ".$ex->getMessage());
		}
		return $macAddresses;
	}
	
	/** Function name: getFilteredEcnetUsers
	 *
	 * This function returns the filtered
	 * ECnet users.
	 * 
	 * @param text $username - user's name
	 * @param text $name - user's username
	 * @return array of ECnet users
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getFilteredEcnetUsers($username, $name){
		try{
			$users = P_Ecnet::getUsers($name, $username);
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'ecnet_user_data', joined to 'users' was not successful! ".$ex->getMessage());
		}
		return $users;
	}
}
