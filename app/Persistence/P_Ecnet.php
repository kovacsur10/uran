<?php

namespace App\Persistence;

/** Class name: P_Ecnet
 *
 * This class is the database persistence layer class
 * for the ECNET module tables.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class P_Ecnet{
	
	/** Function name: addNewUser
	 *
	 * This function inserts a new ecnet user to
	 * the database.
	 * 
	 * @param int $userId - user's identifier
	 * @param datetime $time - creation time
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addNewUser($userId, $time){
		DB::table('ecnet_user_data')
			->insert([
					'user_id' => $userId,
					'valid_time' => $time,
			]);
	}
	
	/** Function name: getUser
	 *
	 * This function returns an ECnet user.
	 *
	 * @param int $userId - user's identifier
	 * @return EcnetUser|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUser($userId){
		return DB::table('ecnet_user_data')
			->where('user_id', '=', $userId)
			->first();
	}
	
	/** Function name: getUsers
	 *
	 * This function returns the filtered ECnet
	 * users by name and username.
	 * 
	 * If null is given for a parameter, then that
	 * filter is not activated.
	 *
	 * @param text|null $name - user's name
	 * @param text|null $username - user's text identifier
	 * @return array of ECnet users
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUsers($name = null, $username = null){
		return DB::table('ecnet_user_data')
			->join('users', 'users.id', '=', 'ecnet_user_data.user_id')
			->when($name !== null, function($query) use($name){
				return $query->where('users.name', 'LIKE', '%'.$name.'%');
			})
			->when($username !== null, function($query) use($username){
				return $query->where('users.username', 'LIKE', '%'.$username.'%');
			})
			->select('users.id as id', 'users.username as username', 'users.name as name', 'ecnet_user_data.money as money', 'ecnet_user_data.valid_time as valid_time', 'ecnet_user_data.mac_slots as mac_slots')
			->get()
			->toArray();
	}
	
	/** Function name: updateUser
	 *
	 * This function updates an ECnet user.
	 *
	 * @param int $userId - user's identifier
	 * @param datetime $validTime - internet access time
	 * @param int $macSlotCount - count of MAC slots
	 * @param int $money - ECnet money
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function updateUser($userId, $validTime, $macSlotCount, $money){
		$updatable = [];
		if($validTime !== null){
			$updatable['valid_time'] = $validTime;	
		}
		if($macSlotCount !== null){
			$updatable['mac_slots'] = $macSlotCount;
		}
		if($money !== null){
			$updatable['money'] = $money;
		}
		DB::table('ecnet_user_data')
			->where('user_id', '=', $userId)
			->update($updatable);
	}
	
	/** Function name: addMacAddress
	 *
	 * This function adds a new MAC address - user
	 * relation to the database.
	 *
	 * @param text $macAddress - MAC address
	 * @param int $userId - user's identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addMacAddress($macAddress, $userId){
		DB::table('ecnet_mac_addresses')
			->insert([
					'user_id' => $userId,
					'mac_address' => $macAddress
			]);
	}
	
	/** Function name: getMacAddress
	 *
	 * This function returns the requested MAC
	 * address data.
	 *
	 * @param text $macAddress - MAC address
	 * @return MACaddress|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getMacAddress($macAddress){
		DB::table('ecnet_mac_addresses')
			->where('mac_address', 'LIKE', $macAddress)
			->first();
	}
	
	/** Function name: getMacAddressesForUser
	 *
	 * This function returns the MAC addresses
	 * for the requested user.
	 *
	 * @param int $userId - user's identifier
	 * @return array of MAC addresses
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getMacAddressesForUser($userId){
		return DB::table('ecnet_mac_addresses')
			->where('user_id', '=', $userId)
			->get()
			->toArray();
	}
	
	/** Function name: removeMacAddress
	 *
	 * This function removes the requested MAC
	 * address data from the database.
	 *
	 * @param text $macAddress - MAC address
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function removeMacAddress($macAddress){
		DB::table('ecnet_mac_addresses')
			->where('mac_address', 'LIKE', $macAddress)
			->delete();
	}
	
	/** Function name: addMacSlotOrder
	 *
	 * This function creates a new MAC slot order.
	 *
	 * @param int $userId - user's identifier
	 * @param text $reason - mac slot order reason
	 * @param datetime $date - creation date
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addMacSlotOrder($userId, $reason, $date){
		DB::table('ecnet_mac_slot_orders')
			->insert([
					'user_id' => $userId,
					'reason' => $reason,
					'order_time' => $date
			]);
	}
	
	/** Function name: getMacSlotOrders
	 *
	 * This function returns the MAC slot orders.
	 *
	 * @return array of MAC slot orders
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getMacSlotOrders(){
		return DB::table('ecnet_mac_slot_orders')
			->join('users', 'users.id', '=', 'ecnet_mac_slot_orders.user_id')
			->select('ecnet_mac_slot_orders.id', 'users.username', 'ecnet_mac_slot_orders.reason', 'ecnet_mac_slot_orders.order_time')
			->get()
			->toArray();
	}
	
	/** Function name: getMacSlotOrder
	 *
	 * This function returns the requested
	 * MAC slot order.
	 *
	 * @param int $orderId - MAC slot order identifier
	 * @return MACslotOrder|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getMacSlotOrder($orderId){
		return DB::table('ecnet_user_data')
			->join('ecnet_mac_slot_orders', 'ecnet_mac_slot_orders.user_id', '=', 'ecnet_user_data.user_id')
			->where('ecnet_mac_slot_orders.id', '=', $orderId)
			->first();
	}
	
	/** Function name: getMacSlotOrdersForUser
	 *
	 * This function returns the requested
	 * user's MAC slot orders.
	 *
	 * @param int $userId - user's identifier
	 * @return array of MAC slot orders
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getMacSlotOrdersForUser($userId){
		return $order = DB::table('ecnet_mac_slot_orders')
			->where('user_id', '=', $userId)
			->get()
			->toArray();
	}
	
	/** Function name: removeMacSlotOrder
	 *
	 * This function removes the requested
	 * MAC slot order from the database.
	 *
	 * @param int $orderId - MAC slot order identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function removeMacSlotOrder($orderId){ 
		DB::table('ecnet_mac_slot_orders')
			->where('id', '=', $orderId)
			->delete();
	}
	
	/** Function name: getValidDate
	 *
	 * This function returns the ECnet internet
	 * access global deadline.
	 *
	 * @return datetime - ECnet internet access date
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getValidDate(){
		return DB::table('ecnet_valid_date')
			->value('valid_date');
	}
	
	/** Function name: updateValidDate
	 *
	 * This function updates the ECnet internet
	 * access deadline.
	 *
	 * @param datetime $newTime - ECnet internet access date
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function updateValidDate($newTime){
		DB::table('ecnet_valid_date')
			->update([
				'valid_date' => $newTime
			]);
	}
}