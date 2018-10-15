<?php

namespace App\Persistence;

use DB;
use App\Classes\Data\EcnetUser;
use App\Classes\Data\MacSlotOrder;
use App\Classes\Data\MacAddress;
use App\Classes\Data\FreePage;
use Carbon\Carbon;

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
	 * @return EcnetData|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUser($userId){
		$rawUser = DB::table('ecnet_user_data')
			->join('users', 'users.id', '=', 'ecnet_user_data.user_id')
			->where('ecnet_user_data.user_id', '=', $userId)
			->select('users.id as id', 'users.username as username', 'users.name as name', 'ecnet_user_data.money as money', 'ecnet_user_data.valid_time as valid_time', 'ecnet_user_data.mac_slots as mac_slots')
			->first();
		if($rawUser === null){
			return null;
		}else{
			$macs = P_Ecnet::getMacAddressesForUser($userId);
			$freePages = P_Ecnet::getFreePagesForUser($userId);
			return new EcnetUser($rawUser->id, $rawUser->name, $rawUser->username, $rawUser->valid_time, $rawUser->mac_slots, $macs, $rawUser->money, $freePages);
		}
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
	 * @return array of EcnetUser
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUsers($name = null, $username = null){
		$rawUsers = DB::table('ecnet_user_data')
			->join('users', 'users.id', '=', 'ecnet_user_data.user_id')
			->when($name !== null, function($query) use($name){
				return $query->where('users.name', 'ILIKE', '%'.$name.'%');
			})
			->when($username !== null, function($query) use($username){
				return $query->where('users.username', 'ILIKE', '%'.$username.'%');
			})
			->where('users.registered', '=', 1)
			->select('users.id as id', 'users.username as username', 'users.name as name', 'ecnet_user_data.money as money', 'ecnet_user_data.valid_time as valid_time', 'ecnet_user_data.mac_slots as mac_slots')
			->get()
			->toArray();
		$users = [];
		foreach($rawUsers as $user){
			$macs = P_Ecnet::getMacAddressesForUser($user->id);
			$freePages = P_Ecnet::getFreePagesForUser($user->id);
			array_push($users, new EcnetUser($user->id, $user->name, $user->username, $user->valid_time, $user->mac_slots, $macs, $user->money, $freePages));
		}
		return $users;
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
	 * @return MacAddress|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getMacAddress($macAddress){
		$address = DB::table('ecnet_mac_addresses')
			->where('mac_address', 'LIKE', $macAddress)
			->first();
		return $address === null ? null : new MacAddress($address->id, $address->mac_address);
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
		$rawAddresses = DB::table('ecnet_mac_addresses')
			->where('user_id', '=', $userId)
			->get()
			->toArray();
		$addresses = [];
		foreach($rawAddresses as $address){
			array_push($addresses, new MacAddress($address->id, $address->mac_address));
		}
		return $addresses;
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
	 * @return array of MacSlotOrder
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getMacSlotOrders(){
		$rawOrders = DB::table('ecnet_mac_slot_orders')
			->join('users', 'users.id', '=', 'ecnet_mac_slot_orders.user_id')
			->select('ecnet_mac_slot_orders.id as id', 'users.username as username', 'ecnet_mac_slot_orders.reason as reason', 'ecnet_mac_slot_orders.order_time as order_time')
			->get()
			->toArray();
		$orders = [];
		foreach($rawOrders as $order){
			array_push($orders, new MacSlotOrder($order->id, $order->reason, $order->order_time, $order->username));
		}
		return $orders;
	}
	
	/** Function name: getMacSlotOrder
	 *
	 * This function returns the requested
	 * MAC slot order.
	 *
	 * @param int $orderId - MAC slot order identifier
	 * @return MacSlotOrder|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getMacSlotOrder($orderId){
		$order = DB::table('ecnet_mac_slot_orders')
			->join('users', 'users.id', '=', 'ecnet_mac_slot_orders.user_id')
			->where('ecnet_mac_slot_orders.id', '=', $orderId)
			->select('ecnet_mac_slot_orders.id as id', 'users.username as username', 'ecnet_mac_slot_orders.reason as reason', 'ecnet_mac_slot_orders.order_time as order_time')
			->first();
		return $order === null ? null : new MacSlotOrder($order->id, $order->reason, $order->order_time, $order->username);
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
	
	/** Function name: getFreePagesForUser
	 *
	 * This function returns the free pages to print
	 * for the requested user.
	 *
	 * @param int $userId - user's identifier
	 * @return array of FreePage
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getFreePagesForUser($userId){
		$today = Carbon::now()->toDateString();
		$rawFreePages = DB::table('ecnet_free_pages')
			->where('user_id', '=', $userId)
			->where('valid_until', '>=', $today.' 00:00:00')
			->where('pages_left', '>', 0)
            ->orderBy('valid_until', 'ASC')
			->get()
			->toArray();
		$freePages = [];
		foreach($rawFreePages as $freePage){
			$freePages[] = new FreePage($freePage->pages_left, $freePage->valid_until);
		}
		return $freePages;
	}

    static function getNumberOfFreePagesForUser($userId){
        $freePages = getFreePagesForUser($userId);
        $sum = 0;
        foreach($freePages as $freePage){
            $sum += $freePage->count();
        }
        return $sum;
    }

    static function useUpFreePages($userId, $amount){
        $sum = 0;
        while($amount > 0){
            $today = Carbon::now()->toDateString();
            $freePages = DB::table('ecnet_free_pages')
                ->where('user_id', '=', $userId)
                ->where('valid_until', '>=', $today.' 00:00:00')
                ->where('pages_left', '>', 0)
                ->orderBy('valid_until', 'ASC')
                ->limit(1)
                ->get()
                ->toArray();
            if(count($freePages) == 0){
                return $sum;
            }
            $freePages = $freePages[0];
            $pages_left = max($freePages->pages_left - $amount, 0);
            $sum += min($amount, $freePages->pages_left);
            $amount -= min($amount, $freePages->pages_left);

            DB::table('ecnet_free_pages')
                ->where('user_id', '=', $userId)
                ->where('valid_until', '=', $freePages->valid_until)
                ->where('pages_left', '=', $freePages->pages_left)
                ->update(['pages_left' => $pages_left]);

        }
        return $sum;
    }
	
	/** Function name: addFreePagesToUser
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
	static function addFreePagesToUser($userId, $pages, $valid_date){
		$already = DB::table('ecnet_free_pages')
			->where('user_id', '=', $userId)
			->where('valid_until', '=', $valid_date)
			->get()
			->toArray();
		if($already !== []){
			DB::table('ecnet_free_pages')
				->where('user_id', '=', $userId)
				->where('valid_until', '=', $valid_date)
				->update([
					'pages_left' => ($pages+$already[0]->pages_left)
				]);
		}else{
			DB::table('ecnet_free_pages')
				->insert([
					'user_id' => $userId,
					'pages_left' => $pages,
					'valid_until' => $valid_date
				]);
		}
	}
}