<?php

namespace App\Classes\Layout;

use Carbon\Carbon;
use App\Persistence\P_Ecnet;
use App\Classes\Data\EcnetUser;
use App\Exceptions\UserNotFoundException;
use App\Classes\Logger;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;
use App\Classes\Database;
use Illuminate\Contracts\Session\Session;

/** Class name: EcnetData
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
class EcnetData extends User{

// PRIVATE DATA

	private $ecnetUser;
	private $validationTime;
	private $ecnetUsers;
	private $filters;
	
// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * Constructor fot the EcnetData class.
	 * 
	 * @param int $userId - user's identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct($userId = 0){
		try{
			$this->ecnetUser = $this->getEcnetUserData($userId);
		}catch(\Exception $ex){
			$this->ecnetUser = null;
		}
		$this->validationTime = $this->getValidationTime();
		$this->ecnetUsers = $this->getEcnetUsers();
		$this->filters = [
			'name' => null,
			'username' => null
		];
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
	public function validationTime() : string{
		return $this->validationTime;
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
		return $this->filters['name'];
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
		return $this->filters['username'];
	}
	
	/** Function name: filterUsers
	 *
	 * This function makes the filtering
	 * of the ECnet admin panel.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function filterUsers(){
		if(session()->has('ecnet_username_filter') && session()->has('ecnet_name_filter')){
			$username = session()->get('ecnet_username_filter');
			$name = session()->get('ecnet_name_filter');
			if($username === ""){
				$username = null;
			}
			if($name === ""){
				$name = null;
			}
			$this->filters['name'] = $name;
			$this->filters['username'] = $username;
			$this->ecnetUsers = $this->getFilteredEcnetUsers($username, $name);
		}
	}
	
	/** Function name: resetFilterUsers
	 *
	 * This function sets the filter fields
	 * of the ECnet admin panel.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function setFilterUsers($username, $name){
		if($username === null){
			session()->put('ecnet_username_filter', '');
		}else{
			session()->put('ecnet_username_filter', $username);
		}
		if($name === null){
			session()->put('ecnet_name_filter', '');
		}else{
			session()->put('ecnet_name_filter',$name);
		}
	}
	
	/** Function name: resetFilterUsers
	 *
	 * This function resets the filter fields
	 * of the ECnet admin panel.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function resetFilterUsers(){
		session()->forget('ecnet_username_filter');
		session()->forget('ecnet_name_filter');
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
	 * @param int $from - first user id
	 * @param int $count - visible count
	 * @return array of Users
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function ecnetUsers($from = 0, $count = 50){
		return $this->toPages($this->ecnetUsers, $from, $count);
	}
	
	/** Function name: register
	 *
	 * This function creates the new
	 * user's ECnet user data.
	 * 
	 * @param int $userId - user's identifier
	 * 
	 * @thrown QueryException when the persistence layer throws an exception.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function register($userId = 0){
		P_Ecnet::addNewUser($userId, Carbon::now()->toDateTimeString());
	}
	
	/** Function name: getEcnetUserData
	 *
	 * This function returns the requested
	 * user's data.
	 *
	 * @param int $userId - user's identifier
	 * @return EcnetUser
	 * 
	 * @throws UserNotFoundException if the user was not found.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getEcnetUserData($userId){
		if($userId === null){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). User id is null!");
			throw new UserNotFoundException();
		}
		try{
			$userData = P_Ecnet::getUser($userId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new UserNotFoundException();
		}
		if($userData === null){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). User was not found!");
			throw new UserNotFoundException();
		}
		return $userData;
	}
	
	//PRINTING CONTROLLER
		
	/** Function name: setMoneyForUser
	 *
	 * This function sets the requested
	 * user's ECnet money to the given
	 * value.
	 * 
	 * @param int $userId - user's identifier
	 * @param int $money - money
	 * 
	 * @throws DatabaseException if an error occures
	 * @throws ValueMismatchException if a parameter value is null.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function setMoneyForUser($userId, $money){
		if($userId === null || $money === null){
			throw new ValueMismatchException("Parameter values cannot be null!");
		}
		try{
			P_Ecnet::updateUser($userId, null, null, $money);
		}catch(\Excetion $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not set the account.");
		}
	}
	
	/** Function name: addFreePagesForUser
	 *
	 * This function adds the requested amount of free
	 * printing pages to the requested user's account.
	 *
	 * @param int $userId - user's identifier
	 * @param int $pages - page
	 * @param datetime $valid_date - user can use the pages until this date
	 *
	 * @throws DatabaseException if an error occures
	 * @throws ValueMismatchException if a parameter value is null.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function addFreePagesForUser($userId, $pages, $valid_date){ //TODO: test it
		if($userId === null || $pages=== null || $valid_date === null){
			throw new ValueMismatchException("Parameter values cannot be null!");
		}
		try{
			$valid_date = $valid_date.' 00:00:00';
			P_Ecnet::addFreePagesToUser($userId, $pages, $valid_date);
		}catch(\Excetion $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not set the free printing account.");
		}
	}
	
	/** Function name: manageMacAddresses
	 *
	 * This function sets the MAC addresses for
	 * the user. It removes the other, already set
	 * addresses as well.
	 *
	 * @param array $addresses - MAC addresses that the user should have
	 *
	 * @throws DatabaseException if an error occures.
	 * @throws ValueMismatchException if the parameter value is unusable.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	//ACCESS CONTROLLER
	public function manageMacAddresses($addresses){
		if($addresses === null || !is_array($addresses)){
			throw new ValueMismatchException("Parameter should be an array.");
		}
		$newAddresses = [];
		$deletableAddresses = [];
		//calculate existing and new addresses
		foreach($addresses as $address){
			if($address !== ""){
				if(!$this->macAddressExists($address)){
					$newAddresses[] = $address;
				}
			}
		}
		//calculate deletable addresses
		foreach($this->ecnetUser->macAddresses() as $mac){
			if(($key = array_search($mac->address(), $addresses)) === false){
				$deletableAddresses[] = $mac->address();
			}
		}
		//commit the changes
		try{
			$userId = $this->user()->id();
			Database::transaction(function() use($deletableAddresses, $newAddresses, $userId){
				foreach($deletableAddresses as $address){
					EcnetData::deleteMacAddress($address);
				}
				foreach($newAddresses as $address){
					EcnetData::insertMacAddress($userId, $address);
				}
			});
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("An error occured at the commitment!");
		}
	}
	
	/** Function name: changeDefaultValidDate
	 *
	 * This function sets the default
	 * valid time for ECnet internet access.
	 * 
	 * @param datetime $newTime - new default datetime
	 * 
	 * @throws ValueMismatchException if a wrongly formatted date is given.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function changeDefaultValidDate($newTime){
		if($newTime === null){
			throw new ValueMismatchException("The date cannot be null!");
		}
		try{
			P_Ecnet::updateValidDate($newTime);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new ValueMismatchException("The date format is wrong!");
		}
	}
	
	/** Function name: activateUserNet
	 *
	 * This function sets the internet valid
	 * time of the requested user.
	 * 
	 * @param int $userId - user's identifier
	 * @param datetime $newTime - new default datetime
	 * 
	 * @throws DatabaseException if an error occures.
	 * @throws ValueMismatchException if a parameter value is null.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function activateUserNet($userId, $newTime){
		if($userId === null || $newTime === null){
			throw new ValueMismatchException("Parameter values cannot be null!");
		}
		try{
			P_Ecnet::updateUser($userId, $newTime, null, null);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not activate the internet access for the user!");
		}
	}
	
	/** Function name: macAddressExists
	 *
	 * This function returns whether the requested MAC address exists in
	 * the database or not.
	 * 
	 * @param text $macAddress - mac address
	 * @return bool - exists or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function macAddressExists($macAddress){
		if($macAddress === null){
			return false;
		}
		$macAddress = strtoupper(str_replace("-", ":", $macAddress));
		if(preg_match("/(?:[A-F0-9]{2}:){5}[A-F0-9]{2}/", $macAddress, $output_array) !== 1){
			return false;
		}
		try{
			$line = P_Ecnet::getMacAddress($macAddress);
		}catch(\Exception $ex){
			$line = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $line !== null;
	}
	
	/** Function name: deleteMacAddress
	 *
	 * This function sets the internet valid
	 * time of the requested user.
	 * 
	 * @param text $macAddress - mac address
	 * 
	 * @throws DatabaseException if the deletion fails.
	 * @throws ValueMismatchException if a parameter value is null.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function deleteMacAddress($macAddress){
		if($macAddress === null){
			throw new ValueMismatchException("The parameter values cannot be null.");
		}
		try{
			$macAddress = strtoupper($macAddress);
			P_Ecnet::removeMacAddress($macAddress);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("MAC address deletion was not successful!");
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
	 * @throws ValueMismatchException if the given MAC address is malformed.
	 * @throws DatabaseException if the addition fails.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function insertMacAddress($userId, $macAddress){
		if($userId === null || $macAddress === null){
			throw new ValueMismatchException("Null values are not allowed as parameter!");
		}
		$macAddress = strtoupper(str_replace("-", ":", $macAddress));
		if(preg_match("/(?:[A-F0-9]{2}:){5}[A-F0-9]{2}/", $macAddress, $output_array) !== 1){
			throw new ValueMismatchException("Malformed MAC address!");
		}
		try{
			P_Ecnet::addMacAddress($macAddress, $userId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("MAC address addition was not successful!");
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
	public static function hasMACSlotOrder($userId){
		try{
			$order = P_Ecnet::getMacSlotOrdersForUser($userId);
		}catch(\Exception $ex){
			$order = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
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
	 * 
	 * @throws DatabaseException if a server error occures.
	 * @throws ValueMismatchException if the parameters are null values.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function addMACSlotOrder($userId, $reason){
		if($userId === null || $reason === null){
			throw new ValueMismatchException("Parameters cannot be null!");
		}
		try{
			Database::transaction(function() use($userId, $reason){
					P_Ecnet::addMacSlotOrder($userId, $reason, Carbon::now()->toDateTimeString());
			});
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Cannot add the MAC slot order!");
		}
	}
	
	/** Function name: getMacSlotOrders
	 *
	 * This function returns the existing
	 * MAC slot orders.
	 * 
	 * @return array of MacSlotOrder
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getMacSlotOrders(){
		try{
			$orders = P_Ecnet::getMacSlotOrders();
		}catch(\Exception $ex){
			$orders = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $orders;
	}
	
	/** Function name: getMacSlotOrderById
	 *
	 * This function returns the requested
	 * MAC slot order.
	 * 
	 * @param int $orderId - user's identifier
	 * @return MacSlotOrder
	 * 
	 * @throws ValueMismatchException if the parameter value is null.
	 * @throws DatabaseException if a database error occures.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getMacSlotOrderById($orderId){
		if($orderId === null){
			throw new ValueMismatchException("Null value as parameter is forbidden!");
		}
		try{
			$order = P_Ecnet::getMacSlotOrder($orderId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("An internal database error occured!");
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
	 * 
	 * @throws ValueMismatchException if the parameter value is null.
	 * @throws ValueMismatchException if the macSlotCount is negative.
	 * @throws DatabaseException if an error occures.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function setMacSlotCountForUser($userId, $macSlotCount){
		if($userId === null || $macSlotCount === null){
			throw new ValueMismatchException("Null value as parameter is forbidden!");
		}
		if($macSlotCount < 0){
			throw new ValueMismatchException("The count of the maximum MAC slots cannot be less, than zero!");
		}
		try{
			P_Ecnet::updateUser($userId, null, $macSlotCount, null);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not set the MAC slot count for the user!");
		}
	}

	/** Function name: deleteMacSlotOrderById
	 *
	 * This function removes the requested
	 * MAC slot order.
	 * 
	 * @param int $orderId - identifier of an order
	 * 
	 * @throws ValueMismatchException if the provided parameter value is null.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function deleteMacSlotOrderById($orderId){
		if($orderId === null){
			throw new ValueMismatchException("The parameter value cannot be null!");
		}
		try{
			P_Ecnet::removeMacSlotOrder($orderId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not delete the MAC slot order!");
		}
	}
	
	/** Function name: checkUserCount
	 *
	 * This function checks the showable user count and
	 * saves session data.
	 * 
	 * @param int $count - user count to show
	 * @return int - corrected user count to shw
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function checkUserCount($count){
		if(($count === null && !session()->has('ecnet_admin_paging')) || ($count !== null && ($count < 1 || 500 < $count))){
			$count = 50;
		}else if($count === null){
			$count = session()->get('ecnet_admin_paging');
		}
		session()->put('ecnet_admin_paging', $count);
		return $count;
	}
	
	/** Function name: getSessionData
	 *
	 * This function returns an array or values, that
	 * should be saved as session data for the user.
	 * 
	 * @return array of mixed - the returned values
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getSessionData(){
		$sessionData = [];
		if(session()->has('ecnet_username_filter')){
			$sessionData['ecnet_username_filter'] = session()->get('ecnet_username_filter');
		}
		if(session()->has('ecnet_name_filter')){
			$sessionData['ecnet_name_filter'] = session()->get('ecnet_name_filter');
		}
		if(session()->has('ecnet_admin_paging')){
			$sessionData['ecnet_admin_paging'] = session()->get('ecnet_admin_paging');
		}
		return $sessionData;
	}
	
// PRIVATE FUNCTIONS
	
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $datetime;
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
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $users;
	}
}
